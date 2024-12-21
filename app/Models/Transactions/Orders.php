<?php

namespace App\Models\Transactions;

use App\Models\Catalogs\Distributors;
use App\Models\Users\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;


    public const STATUS_PENDING = 0;
    public const STATUS_PROCESSING = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_CANCELED = 3;

    public $timestamps = true;
    protected $table = 'orders';
    protected $fillable = [
        'distributor_id',
        'employee_id',
        'date',
        'status',
        'note',
    ];

    public function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing'
        ];
    }

    public function importReceipts()
    {
        return $this->hasMany(ImportReceipts::class, 'order_id', 'id');
    }

    public function distributors()
    {
        return $this->belongsTo(Distributors::class, 'distributor_id', 'id');
    }

    public function employees()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(OrderDetails::class, 'order_id', 'id');
    }
}
