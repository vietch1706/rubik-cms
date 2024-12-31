<?php

namespace App\Schema;

use App\Models\Transactions\Orders\Orders;
use App\Models\Users\Users;

class OrderSchema
{
    private Orders $orders;

    public function __construct(
        Orders $order,
    )
    {
        $this->orders = $order;
    }

    public function convertData()
    {
        $employeeFullname = Users::select('id', 'first_name', 'last_name')
            ->where('id', $this->orders->user_id)
            ->get(['id', 'first_name', 'last_name'])
            ->pluck('fullName', 'id')
            ->toArray();
        $distributor = $this->orders->distributor()->pluck('name', 'id')->toArray();
        return [
            'id' => $this->orders->id,
            'order_number' => $this->orders->order_number,
            'distributor' => $distributor,
            'employee' => $employeeFullname,
            'date' => $this->orders->date,
            'status' => $this->orders->status,
            'note' => $this->orders->note,
            'created_at' => $this->orders->created_at,
            'updated_at' => $this->orders->updated_at,
        ];
    }
}
