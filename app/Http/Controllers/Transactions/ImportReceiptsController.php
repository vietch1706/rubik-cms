<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transactions\CsvImportRequest;
use App\Http\Resources\ImportReceiptDetailsResource;
use App\Http\Resources\ImportReceiptsResource;
use App\Jobs\ProcessProduct;
use App\Models\Catalogs\Products;
use App\Models\Transactions\ImportReceiptDetails;
use App\Models\Transactions\ImportReceipts;
use App\Models\Transactions\OrderDetails;
use App\Models\Transactions\Orders;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function array_combine;
use function back;
use function fgetcsv;
use function fopen;
use function redirect;
use function request;
use function response;
use function view;

class ImportReceiptsController extends Controller
{
    public const PAGE_LIMIT = 20;
    private ImportReceipts $importReceipts;
    private ImportReceiptDetails $importReceiptDetails;
    private Orders $orders;
    private Products $products;

    public function __construct(
        ImportReceipts       $importReceipt,
        ImportReceiptDetails $importReceiptDetail,
        Orders               $order,
        Products             $product
    )
    {
        $this->importReceipts = $importReceipt;
        $this->importReceiptDetails = $importReceiptDetail;
        $this->orders = $order;
        $this->products = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $importReceipts = $this->importReceipts
            ->paginate(self::PAGE_LIMIT);
        return view('transactions.import_receipts.list', [
            'importReceipts' => ImportReceiptsResource::collection($importReceipts)->toArray(request()),
            'link' => $importReceipts->links(),
        ]);
    }

    public function importView()
    {
        $orders = $this->orders
            ->select('id', 'order_no')
            ->whereNot('status', Orders::STATUS_FULLY_IMPORTED)
            ->whereNot('status', Orders::STATUS_CANCELED)
            ->whereDoesntHave('importReceipts', function ($query) {
                $query->where('status', $this->importReceipts::STATUS_PENDING);
            })
            ->pluck('order_no', 'id');
        return view('transactions.import_receipts.import', [
            'orders' => $orders,
        ]);
    }

    public function previewImport(CsvImportRequest $request)
    {
        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $headers = fgetcsv($handle);
        $firstRow = fgetcsv($handle);
        if ($firstRow) {
            $firstRow = array_combine($headers, $firstRow);
        }
        fclose($handle);
        $importReceipt = new $this->importReceipts;
        $importReceipt->order_no = $request->input('order_number');
        $importReceipt->employee_id = Auth::user()->id;
        $importReceipt->date = Carbon::now();
        $importReceipt->status = ImportReceipts::STATUS_PENDING;
        $importReceipt->save();
        $filename = $request->input('order_number') . '_' . $importReceipt->id . '.' . $file->getClientOriginalExtension();
        $file->storeAs('csv', $filename);
        return view('transactions.import_receipts.preview-import', [
            'headers' => $headers,
            'filename' => $filename,
            'firstRow' => $firstRow,
        ]);
    }

    public function processImport(CsvImportRequest $request)
    {
        $filename = $request->input('filename');
        $filepath = storage_path('app/csv/' . $filename);
        if (!file_exists($filepath)) {
            return response()->json(['error' => 'File not found']);
        }
        $handle = fopen($filepath, 'r');
        $data = [];
        $headers = fgetcsv($handle);
        while (($record = fgetcsv($handle)) !== false) {
            $data[] = array_combine($headers, $record);
        }
        preg_match('/_(.*?)\./', $filename, $importReceiptId);
        $importReceiptId = $importReceiptId[1];
        DB::beginTransaction();
        try {
            foreach ($data as $key => $row) {
                $currentProduct = $this->products
                    ->select('id')
                    ->where('sku', $row['sku'])
                    ->first();
                $importReceiptDetail = new $this->importReceiptDetails;
                $importReceiptDetail->import_receipt_id = $importReceiptId;
                $importReceiptDetail->product_id = $currentProduct->id;
                $importReceiptDetail->import_date = Carbon::now();
                $importReceiptDetail->quantity = $row['quantity'];
                $importReceiptDetail->price = $row['price'];
                $importReceiptDetail->save();
            }
            DB::commit();
            Storage::delete('app/csv/' . $filename);
            return redirect()->route('receipts')->with('success', 'Import Successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function approveReceipt($id)
    {
        $rightOrder = false;
        $importReceipt = $this->importReceipts
            ->where('id', $id)
            ->where('status', ImportReceipts::STATUS_PENDING)
            ->first();
        $order = $importReceipt->order()->first();
        $orderDetails = $order
            ->details()
            ->whereNot('status', OrderDetails::STATUS_FULLY_IMPORTED)
            ->get()
            ->keyBy('product_id');
        $importDetails = $importReceipt
            ->details()
            ->get()
            ->keyBy('product_id');
        DB::beginTransaction();
        try {
            foreach ($orderDetails as $productId => $orderDetail) {
                if ($importDetails->has($productId)) {
                    $rightOrder = true;
                    $importQuantity = $importDetails[$productId]->quantity;
                    $remainQuantity = $orderDetail->quantity - $importQuantity;
                    $orderDetail->status =
                        $remainQuantity > 0 ?
                            OrderDetails::STATUS_PARTIALLY_IMPORTED :
                            OrderDetails::STATUS_FULLY_IMPORTED;
                    $orderDetail->save();
                }
            }
            if (!$rightOrder) {
                $importReceipt->status = ImportReceipts::STATUS_CANCELLED;
                $importReceipt->save();
                DB::commit();
                return back()->with('error', 'Order not found!');
            }
            ProcessProduct::dispatch($importReceipt);
            $checkOrderStatus = $order->details->every(function ($orderDetail) {
                return $orderDetail->status === Orders::STATUS_FULLY_IMPORTED;
            });
            if ($checkOrderStatus) {
                $order->status = Orders::STATUS_FULLY_IMPORTED;
            } else {
                $order->status = Orders::STATUS_PARTIALLY_IMPORTED;
            }
            $order->save();
            $importReceipt->status = ImportReceipts::STATUS_COMPLETE;
            $importReceipt->save();
            DB::commit();
            return back()->with('success', 'Import Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function preview($id)
    {
        //
        $importReceipt = new ImportReceiptsResource($this->importReceipts->find($id));
        $importReceiptDetails = $importReceipt->details()->get();
        return view('transactions.import_receipts.preview', [
            'importReceipt' => $importReceipt->toArray(request()),
            'importReceiptDetails' => ImportReceiptDetailsResource::collection($importReceiptDetails)->toArray(request()),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        //
        $ids = $request->ids;
        $completeReceipts = $this->importReceipts->whereIn('id', $ids)
            ->where('status', $this->importReceipts::STATUS_COMPLETE)
            ->pluck('id');
        if ($completeReceipts->isNotEmpty()) {
            return response()->json([
                'message' => "Can'\t delete complete receipts:" . $completeReceipts->join(', ')
            ]);
        }
        $this->importReceipts->destroy($ids);
        $this->importReceipts->whereIn('id', $ids)->each(function ($receipt) {
            $receipt->details()->destroy();
        });
        return response()->json([
            "message" => 'Receipts have been deleted'
        ]);
    }

}
