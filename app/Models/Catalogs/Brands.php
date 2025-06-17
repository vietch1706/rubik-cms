<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    use HasFactory;

    protected $table = 'product_brands';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'image',
    ];

    public function products()
    {
        return $this->hasMany(Products::class, 'brand_id', 'id');
    }
}
