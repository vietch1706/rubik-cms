<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categories extends Model
{
    use HasFactory;

    public const SLUG_BLOG = 'blog';
    public const SLUG_BRANDS = 'brands';
    public $timestamps = true;
    protected $table = 'product_categories';
    protected $fillable = [
        'name',
        'parent_id',
        'slug',
    ];

    protected static function booted()
    {
        static::saving(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name, '-');
            }
        });
    }

    public function parentCategory()
    {
        return $this->belongsTo(Categories::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Products::class, 'category_id', 'id');
    }
}
