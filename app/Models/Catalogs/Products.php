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

    public const STATUS_AVAILABLE = 1;
    public const STATUS_UNAVAILABLE = 0;

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
        'status' => self::STATUS_AVAILABLE,
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
        return $this->belongsToMany(Distributors::class, 'distributors_products', 'product_id', 'distributor_id')->withPivot('price');
    }

    public function getMagneticOptions()
    {
        return [
            self::MAGNETIC_NO => 'No',
            self::MAGNETIC_YES => 'Yes',
        ];
    }

    public function getStatusOptions()
    {
        return [
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_UNAVAILABLE => 'Unavailable',
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

    public static function getProductByDistributorId($id)
    {
        dd(Products::with('distributors')->where('distributors.id', $id)->get());
    }
}
