<?php

namespace App\Models\Transactions;

use App\Models\Catalogs\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 0;
    public const STATUS_PARTIALLY_IMPORTED = 1;
    public const STATUS_FULLY_IMPORTED = 2;
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
        'status' => self::STATUS_PENDING,
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function products()
    {
        return $this->hasMany(Products::class, 'id', 'product_id');
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

}
