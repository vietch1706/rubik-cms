<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transactions\OrderRequest;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\OrdersResource;
use App\Models\Catalogs\Distributors;
use App\Models\Transactions\ImportReceipts;
use App\Models\Transactions\OrderDetails;
use App\Models\Transactions\Orders;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function back;
use function redirect;
use function request;
use function response;
use function view;

class OrdersController extends Controller
{
    public const PAGE_LIMIT = 20;
    private Orders $orders;
    private OrderDetails $orderDetails;
    private Distributors $distributors;
    private ImportReceipts $importReceipts;

    public function __construct(
        Orders         $order,
        OrderDetails   $orderDetail,
        Distributors   $distributor,
        ImportReceipts $importReceipt
    )
    {
        $this->orders = $order;
        $this->orderDetails = $orderDetail;
        $this->distributors = $distributor;
        $this->importReceipts = $importReceipt;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $orders = $this->orders->paginate(self::PAGE_LIMIT);
        return view('transactions.orders.list', [
            'orders' => OrdersResource::collection($orders)->toArray(request()),
            'link' => $orders->links(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $distributors = $this->distributors->pluck('name', 'id');
        return view('transactions.orders.create', [
            'current_employee' => [
                'full_name' => Auth::user()->full_name,
                'id' => Auth::user()->id,
            ],
            'distributors' => $distributors,
            'statuses' => $this->orders->getStatusOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(OrderRequest $request)
    {
        //
        $products = json_decode($request->input('products'), true);
        DB::beginTransaction();
        $orders = new $this->orders;
        try {
            $orders->distributor_id = $request->input('distributor_id');
            $orders->employee_id = $request->input('employee_id');
            $orders->date = $request->input('date');
            $orders->status = $request->input('status');
            $orders->note = $request->input('note');
            $orders->save();
            foreach ($products as $product) {
                $orderDetails = new $this->orderDetails;
                $orderDetails->order_id = $orders->id;
                $orderDetails->product_id = $product['id'];
                $orderDetails->quantity = $product['quantity'];
                $orderDetails->price = $product['price'];
                $orderDetails->save();
            }
            DB::commit();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('orders')->with('success', 'Created Successfully!');
            }
            return back()->with('success', 'Created Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function preview($id)
    {
        $order = new OrdersResource($this->orders->find($id));
        $orderDetails = $order->details()->get();
        $isImported = false;
        foreach ($orderDetails as $key => $orderDetail) {
            if ($orderDetail->status == OrderDetails::STATUS_PARTIALLY_IMPORTED) {
                $isImported = true;
                $importedDetails = $this->importReceipts
                    ->where('order_no', $order->order_no)
                    ->first()
                    ->details()
                    ->where('product_id', $orderDetail->product_id)
                    ->first();
                $orderDetail->imported_quantity = $importedDetails->quantity;
            }
        }
        return view('transactions.orders.preview', [
            'order' => $order->toArray(request()),
            'orderDetails' => OrderDetailsResource::collection($orderDetails)->toArray(request()),
            'isImported' => $isImported,
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
    public function destroy(Request $request)
    {
        //
        $ids = $request->ids;
        $pendingOrders = $this->orders->whereIn('id', $ids)
            ->whereNot('status', $this->orders::STATUS_PENDING)
            ->pluck('id');
        if ($pendingOrders->isNotEmpty()) {
            return response()->json([
                'message' => "Can'\t delete none pending orders:" . $pendingOrders->join(', ')
            ]);
        }
        $this->orders->destroy($ids);
        $this->orders->whereIn('id', $ids)->each(function ($order) {
            $order->details()->destroy();
        });
        return response()->json([
            "message" => 'Orders have been deleted'
        ]);
    }
}
