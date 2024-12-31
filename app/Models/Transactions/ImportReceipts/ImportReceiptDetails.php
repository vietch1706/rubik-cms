<?php

namespace App\Models\Transactions\ImportReceipts;

use App\Models\Catalogs\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportReceiptDetails extends Model
{
    use HasFactory;

    public const IS_HIDDEN_YES = 1;
    public const IS_HIDDEN_NO = 0;
    public const IS_IMPORTED_YES = 1;
    public const IS_IMPORTED_NO = 0;

    public const STATUS_PROCESSING = 0;
    public const STATUS_COMPLETED = 1;
    public $timestamps = true;
    protected $table = 'import_receipt_details';
    protected $fillable = [
        'import_receipt_id',
        'product_id',
        'import_date',
        'quantity',
        'price',
    ];

    public function importReceipt()
    {
        return $this->belongsTo(ImportReceipts::class, 'import_receipt_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
