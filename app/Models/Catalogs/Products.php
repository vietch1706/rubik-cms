<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Products extends Model
{
    use HasFactory;

    public const MAGNETIC_YES = 1;
    public const MAGNETIC_NO = 0;


    public $timestamps = true;
    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'release_date',
        'weight',
        'magnetic',
        'price',
        'box_weight',
        'image'
    ];

    protected $attributes = [
        'magnetic' => self::MAGNETIC_NO,
        'quantity' => 0,
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

    public function getMagneticOptions()
    {
        return [
            self::MAGNETIC_NO => 'No',
            self::MAGNETIC_YES => 'Yes',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brands::class, 'brand_id', 'id');
    }

    public function textMagnetic(): Attribute
    {
        return new Attribute(
            get: fn($value, $attribute) => match ($attribute['magnetic']) {
                self::MAGNETIC_YES => 'Yes',
                self::MAGNETIC_NO => 'No',
            },
        );
    }
}
