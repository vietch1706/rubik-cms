<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Brands extends Model
{
    use HasFactory;

    protected $table = 'brands';
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
