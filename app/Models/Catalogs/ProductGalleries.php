<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGalleries extends Model
{
    use HasFactory;
    protected $table = 'product_galleries';
    public $timestamps = true;

    protected $fillable =[
        'product_id',
        'image',
    ];
    public function product() {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
