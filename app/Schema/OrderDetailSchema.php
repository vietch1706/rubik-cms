<?php

namespace App\Schema;

use App\Models\Transactions\OrderDetails;

class OrderDetailSchema
{
    private OrderDetails $orderDetails;

    public function __construct(
        OrderDetails $orderDetail,
    )
    {
        $this->orderDetails = $orderDetail;
    }

    public function convertData()
    {
        $product = $this->orderDetails->products()->pluck('name', 'id')->toArray();
        return [
            'id' => $this->orderDetails->id,
            'product' => $product,
            'price' => $this->orderDetails->price,
            'quantity' => $this->orderDetails->quantity,
            'created_at' => $this->orderDetails->created_at,
            'updated_at' => $this->orderDetails->updated_at,
        ];
    }

}
