<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportReceipts extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 0;
    public const STATUS_COMPLETE = 1;

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

    public function details()
    {
        return $this->hasMany(ImportReceiptDetails::class, 'import_receipt_id', 'id');
    }

    public function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETE => 'Complete'
        ];
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_no', 'order_number');
    }
}
