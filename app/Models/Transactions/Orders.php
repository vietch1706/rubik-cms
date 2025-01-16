<?php

namespace App\Models\Transactions;

use App\Helper\Helper;
use App\Models\Catalogs\Distributors;
use App\Models\Users\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function strlen;

class Orders extends Model
{
    use HasFactory;

    private const ORDER_NO_LENGTH = 10;
    public const STATUS_PENDING = 0;
    public const STATUS_PARTIALLY_IMPORTED = 1;
    public const STATUS_FULLY_IMPORTED = 2;
    public const STATUS_CANCELED = 3;

    public $timestamps = true;
    protected $table = 'orders';
    protected $fillable = [
        'order_no',
        'distributor_id',
        'employee_id',
        'date',
        'status',
        'note',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_no = self::generateUniqueOrderNo('ORD-');
        });
    }

    public static function generateUniqueOrderNo(?string $prefix = null)
    {
        $length = self::ORDER_NO_LENGTH - strlen($prefix ?? '');

        do {
            $order_no = ($prefix ?? '') . Helper::generateRandomString($length, 3);
        } while (self::where('order_no', $order_no)->exists());

        return $order_no;
    }

    public function details()
    {
        return $this->hasMany(OrderDetails::class, 'order_id', 'id');
    }

    public function importReceipts()
    {
        return $this->hasOne(ImportReceipts::class, 'order_no', 'order_no');
    }

    public function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PARTIALLY_IMPORTED => 'Partially Imported',
            self::STATUS_FULLY_IMPORTED => 'Fully Imported',
            self::STATUS_CANCELED => 'Canceled',
        ];
    }

    public function distributor()
    {
        return $this->belongsTo(Distributors::class, 'distributor_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }
}
