<?php

namespace App\Models\Transactions\Orders;

use App\Helper\Helper;
use App\Models\Catalogs\Distributors;
use App\Models\Transactions\ImportReceipts\ImportReceipts;
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
        'order_number',
        'distributor_id',
        'employee_id',
        'date',
        'status',
        'note',
    ];

    public static function generateUniqueOrderNo(?string $prefix = null)
    {
        $length = self::ORDER_NO_LENGTH - strlen($prefix ?? '');

        do {
            $order_number = ($prefix ?? '') . Helper::generateRandomString($length, 3);
        } while (self::where('order_number', $order_number)->exists());

        return $order_number;
    }

    public function details()
    {
        return $this->hasMany(OrderDetails::class, 'order_id', 'id');
    }

    public function importReceipt()
    {
        return $this->hasOne(ImportReceipts::class, 'order_no', 'order_number');
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
