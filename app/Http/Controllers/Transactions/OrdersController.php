<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Distributors;
use App\Models\Transactions\Orders;
use App\Models\Users\Employees;
use App\Models\Users\Users;
use Illuminate\Http\Request;
use function view;

class OrdersController extends Controller
{
    private Orders $orders;
    private Employees $employees;
    private Distributors $distributors;

    public const PAGE_LIMIT = 20;

    public function __construct(
        Orders       $order,
        Employees    $employee,
        Distributors $distributor
    )
    {
        $this->orders = $order;
        $this->employees = $employee;
        $this->distributors = $distributor;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $orders = $this->orders
            ->paginate(self::PAGE_LIMIT);
        return view('transactions.orders.list', [
            'orders' => $orders,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $employees = $this->employees->with('users')->get()->pluck('users.fullName', 'id');
        $distributors = $this->distributors->pluck('name', 'id');
        return view('transactions.orders.create', [
            'employees' => $employees,
            'distributors' => $distributors,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
