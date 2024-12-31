<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transactions\CsvImportRequest;
use App\Models\Transactions\ImportReceipts\ImportReceiptDetails;
use App\Models\Transactions\ImportReceipts\ImportReceipts;
use App\Models\Transactions\Orders\OrderDetails;
use App\Models\Transactions\Orders\Orders;
use App\Models\Utilities\CsvData;
use App\Schema\ImportReceiptDetailSchema;
use App\Schema\ImportReceiptSchema;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function array_column;
use function array_combine;
use function back;
use function fgetcsv;
use function fopen;
use function in_array;
use function redirect;
use function view;

class ImportReceiptsController extends Controller
{
    public const PAGE_LIMIT = 20;
    private ImportReceipts $importReceipts;
    private ImportReceiptDetails $importReceiptDetails;
    private Orders $orders;

    public function __construct(
        ImportReceipts       $importReceipt,
        ImportReceiptDetails $importReceiptDetail,
        Orders               $order
    )
    {
        $this->importReceipts = $importReceipt;
        $this->importReceiptDetails = $importReceiptDetail;
        $this->orders = $order;
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
        foreach ($importReceipts as $key => $importReceipt) {
            $importReceiptSchema = new ImportReceiptSchema($importReceipt);
            $importReceipts[$key] = $importReceiptSchema->convertData();
        }
        return view('transactions.import_receipts.list', [
            'importReceipts' => $importReceipts,
        ]);
    }

    public function importView()
    {
        $orders = $this->orders
            ->select('id', 'order_number')
            ->whereNot('status', Orders::STATUS_FULLY_IMPORTED)
            ->whereNot('status', Orders::STATUS_CANCELED)
            ->pluck('order_number', 'id');
        return view('transactions.import_receipts.import', [
            'orders' => $orders,
        ]);
    }

    public function previewImport(CsvImportRequest $request)
    {
        $file = $request->file('csv_file');
        $filename = $request->input('order_number') . '.' . $file->getClientOriginalExtension();
        $handle = fopen($file->getRealPath(), 'r');
        $headers = fgetcsv($handle);
        $rows = [];
        while (($record = fgetcsv($handle)) !== FALSE) {
            $rows[] = array_combine($headers, $record);
        }
        $csvData = new CsvData();
        $csvData->csv_filename = $filename;
        $csvData->csv_header = CsvData::HEADER_YES;
        $csvData->csv_data = json_encode($rows);
        $csvData->save();
        fclose($handle);
        $firstRow = $rows[0] ?? null;
        $importReceipt = new $this->importReceipts;
        $importReceipt->order_no = $request->input('order_number');
        $importReceipt->user_id = Auth::user()->id;
        $importReceipt->date = Carbon::now();
        $importReceipt->status = ImportReceipts::STATUS_PENDING;
        $importReceipt->save();
        if ($request->input('action') === 'import_and_close') {
            return redirect()->route('orders')->with('success', 'Import Successfully!');
        }
        return back()->with('success', 'Import Successfully!');
//        return view('transactions.import_receipts.preview-import', [
//            'headers' => $headers,
//            'filename' => $filename,
//            'firstRow' => $firstRow,
//        ]);
    }

    public function approveReceipt($id)
    {
        $importReceipt = $this->importReceipts
            ->with('order.details')
            ->where('id', $id)
            ->where('status', ImportReceipts::STATUS_PENDING)
            ->first();
        $order = $importReceipt->order;
        $orderDetails = $order->details;
        $filename = $importReceipt->first()->order_no . '.csv';
        $csvData = CsvData::where('csv_filename', $filename)
            ->orderBy('created_at', 'desc')
            ->first();
        $importDataList = json_decode($csvData->csv_data, true);

        $matchingDetails = $orderDetails->filter(function ($detail) use ($importDataList) {
            $productSku = $detail->product()->pluck('sku', 'id');
            return in_array($productSku[$detail->product_id], array_column($importDataList, 'sku'));
        });
        if ($matchingDetails->isEmpty()) {
            return back()->with('error', 'The products is not found in the order');
        }
        DB::beginTransaction();
        try {

            foreach ($importDataList as $importData) {
                foreach ($matchingDetails as $orderDetail) {
                    $productSku = $orderDetail->product()->pluck('sku', 'id');
                    if ($importData['sku'] == $productSku[$orderDetail->product_id]) {
                        $remainQuantity = $orderDetail->quantity - $importData['quantity'];
                        $importReceiptDetail = new $this->importReceiptDetails;
                        $importReceiptDetail->import_receipt_id = $importReceipt->id;
                        $importReceiptDetail->product_id = $orderDetail->product_id;
                        $importReceiptDetail->quantity = $importData['quantity'];
                        $importReceiptDetail->price = $importData['price'];
                        $orderDetail->status =
                            $remainQuantity > 0 ?
                                OrderDetails::STATUS_PARTIALLY_IMPORTED :
                                OrderDetails::STATUS_FULLY_IMPORTED;
                        $orderDetail->save();
                        $importReceiptDetail->save();
                    }
                }
            }
            $importReceipt->status = ImportReceipts::STATUS_COMPLETE;
            $importReceipt->save();
            $newOrder = $this->orders->where('order_number', $importReceipt->order_no)->first();
            $checkOrderStatus = $newOrder->details->every(function ($orderDetail) {
                return $orderDetail->status === Orders::STATUS_FULLY_IMPORTED;
            });
            if ($checkOrderStatus) {
                $newOrder->status = Orders::STATUS_FULLY_IMPORTED;
            } else {
                $newOrder->status = Orders::STATUS_PARTIALLY_IMPORTED;
            }
            $newOrder->save();
            $csvData->delete();
            DB::commit();
            return back()->with('success', 'Created Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    public function preview($id)
    {
        //
        $importReceipts = $this->importReceipts->find($id);
        $importReceiptSchema = new ImportReceiptSchema($importReceipts);
        $importReceiptDetails = $importReceipts->details()->get();
        foreach ($importReceiptDetails as $key => $importReceiptDetail) {
            $importReceiptDetailSchema = new ImportReceiptDetailSchema($importReceiptDetail);
            $importReceiptDetails[$key] = $importReceiptDetailSchema->convertData();
        }
        return view('transactions.import_receipts.preview', [
            'importReceipts' => $importReceiptSchema->convertData(),
            'importReceiptDetails' => $importReceiptDetails,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
