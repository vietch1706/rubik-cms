<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributors extends Model
{
    use HasFactory;

    protected $table = 'product_distributors';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
    ];

    public function products()
    {
        return $this->belongsToMany(Products::class, 'distributors_products', 'distributor_id', 'product_id')->withPivot('price');
    }
}
