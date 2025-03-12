<?php

namespace App\Models\Transactions;

use App\Models\Users\Customers;
use App\Models\Users\Employees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 0;
    public const STATUS_PAYMENT_CONFIRM = 1;
    public const STATUS_PROCESS = 2;
    public const STATUS_SHIPPED = 3;
    public const STATUS_DELIVERED = 4;
    public const STATUS_COMPLETED = 5;
    public const STATUS_CANCELLED = 6;
    public const STATUS_REFUNDED = 7;

    public $timestamps = true;
    protected $table = 'invoices';
    protected $fillable = [
        'customer_id',
        'employee_id',
        'date',
        'note'
    ];
    protected $attributes = [
        'customer_id' => null,
        'status' => self::STATUS_PENDING,
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(InvoiceDetails::class, 'invoice_id', 'id');
    }

    public function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PAYMENT_CONFIRM => 'Payment Confirm',
            self::STATUS_PROCESS => 'Processing',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_REFUNDED => 'Refunded',
        ];
    }
}
