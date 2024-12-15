<?php

namespace App\Models\Transactions;

use App\Models\Catalogs\Distributors;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';

    public $timestamps = true;
    protected $fillable = [
        'distributor_id',
        'employee_id',
        'date',
        'status',
        'note',
    ];

    public function distributors()
    {
        return $this->belongsTo(Distributors::class, 'distributor_id', 'id');
    }
}
