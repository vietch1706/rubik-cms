<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportReceipts extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 0;
    public const STATUS_PARTIALLY_IMPORTED = 1;
    public const STATUS_FULLY_IMPORTED = 2;
    public const STATUS_FAILED = 3;

    public $timestamps = true;
    protected $table = 'import_receipts';
    protected $fillable = [
        'order_id',
        'date',
        'status'
    ];
    protected $attributes = [
        'status' => 0,
    ];

    public function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PARTIALLY_IMPORTED => 'Partially Imported',
            self::STATUS_FULLY_IMPORTED => 'Fully Imported',
            self::STATUS_FAILED => 'Failed',
        ];
    }

    public function orders()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(ImportReceiptDetails::class, 'import_receipt_id', 'id');
    }
}
