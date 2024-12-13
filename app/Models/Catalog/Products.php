<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Products extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'release_date',
        'weight',
        'type',
        'magnetic',
        'price',
        'box_weight',
        'quantity',
        'unit',
        'image'
    ];

    protected static function booted()
    {
        static::saving(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name, '-');
            }
        });
    }

    public function distributors()
    {
        return $this->belongsToMany(Products::class, 'distributors_products', 'product_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brands::class, 'brand_id', 'id');
    }
}
