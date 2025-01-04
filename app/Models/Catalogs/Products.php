<?php

namespace App\Models\Catalogs;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function strlen;

class Products extends Model
{
    use HasFactory;

    public const MAGNETIC_YES = 1;
    public const MAGNETIC_NO = 0;

    public const STATUS_AVAILABLE = 1;
    public const STATUS_UNAVAILABLE = 0;
    public const SKU_LENGTH = 10;

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

    public static function generateUniqueOrderNo(?string $prefix = null)
    {
        $length = self::SKU_LENGTH - strlen($prefix ?? '');

        do {
            $sku = ($prefix ?? '') . Helper::generateRandomString($length, 3);
        } while (self::where('sku', $sku)->exists());

        return $sku;
    }

    protected static function booted()
    {
        static::saving(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name, '-');
            }
        });
        static::updated(function ($product) {
            $product->updatePrice();
        });
    }

    public function updatePrice()
    {
        $minPrice = DB::table('distributors_products')
            ->where('product_id', $this->product_id)
            ->min('price');

        if ($minPrice) {
            DB::table('products')
                ->where('id', $this->product_id)
                ->update(['price' => $minPrice]);
        }
    }

    /**
     * Scope a query to only include users of a given type.
     *
     * @param Builder $query
     * @param int $id
     * @return Builder
     */
    public function scopeGetByDistributorId($query, $id)
    {
        return $query->whereHas('distributors', function ($q) use ($id) {
            $q->where('id', $id);
        })->with(['distributors']);
    }

    public function scopeGetByCategoryId($query, $id)
    {
        return $query->where('category_id', $id);
    }

    public function scopeGetByBrandId($query, $id)
    {
        return $query->where('brand_id', $id);
    }

    public function distributors()
    {
        return $this->belongsToMany(Distributors::class, 'distributors_products', 'product_id', 'distributor_id')->withPivot('price');
    }

    public function galleries()
    {
        return $this->hasMany(ProductGalleries::class, 'product_id', 'id');
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

    public function scopeGetBySlug($query, $slug)
    {
        return $query->with('category', 'brand')->where('slug', $slug);
    }

    public function scopeGetById($query, $id)
    {
        return $query->with('category', 'brand')->where('id', $id);
    }
}
