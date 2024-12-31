<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transactions\OrderRequest;
use App\Models\Catalogs\Distributors;
use App\Models\Transactions\ImportReceipts\ImportReceipts;
use App\Models\Transactions\Orders\OrderDetails;
use App\Models\Transactions\Orders\Orders;
use App\Schema\OrderDetailSchema;
use App\Schema\OrderSchema;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function back;
use function redirect;
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
        $orders = $this->orders
            ->paginate(self::PAGE_LIMIT);
        foreach ($orders as $key => $order) {
            $orderSchema = new OrderSchema($order);
            $orders[$key] = $orderSchema->convertData();
        }
        return view('transactions.orders.list', [
            'orders' => $orders,
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
            $orders->user_id = $request->input('employee_id');
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
        $order = $this->orders->find($id);
        $orderSchema = new OrderSchema($order);
        $orderDetails = $order->details()->get();
        $isImported = false;
        foreach ($orderDetails as $key => $orderDetail) {
            if ($orderDetail->status == OrderDetails::STATUS_PARTIALLY_IMPORTED) {
                $isImported = true;
                $importedDetails = $this->importReceipts
                    ->where('order_no', $order->order_number)
                    ->first()
                    ->details()
                    ->where('product_id', $orderDetail->product_id)
                    ->first();
                $orderDetail->imported_quantity = $importedDetails->quantity;
            }
            $orderDetailSchema = new OrderDetailSchema($orderDetail);
            $orderDetails[$key] = $orderDetailSchema->convertData();
        }
        return view('transactions.orders.preview', [
            'orders' => $orderSchema->convertData(),
            'orderDetails' => $orderDetails,
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
    public function destroy($id)
    {
        //
    }
}
