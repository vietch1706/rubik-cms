<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributors extends Model
{
    use HasFactory;

    protected $table = 'distributors';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
    ];

    public function products()
    {
        return $this->belongsToMany(Distributors::class, 'distributors_products', 'distributor_id', 'id');
    }
}
