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
        $product = $this->orderDetails->product()
            ->select('id', 'name', 'sku');
        return [
            'id' => $this->orderDetails->id,
            'product' => $product
                ->pluck('name', 'id')
                ->toArray(),
            'sku' => $product->first()->sku,
            'status' => $this->orderDetails->status,
            'price' => $this->orderDetails->price,
            'quantity' => $this->orderDetails->quantity,
            'imported_quantity' => $this->orderDetails->imported_quantity,
            'created_at' => $this->orderDetails->created_at,
            'updated_at' => $this->orderDetails->updated_at,
        ];
    }

}
