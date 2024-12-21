<?php

namespace App\Models\Transactions;

use App\Models\Catalogs\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'order_details';
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];
    protected $attributes = [
        'quantity' => 1,
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    public function orders()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

}
