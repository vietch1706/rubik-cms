<?php

namespace App\Models\Transactions;

use App\Models\Catalogs\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportReceiptDetails extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'import_receipt_details';
    protected $fillable = [
        'import_receipt_id',
        'product_id',
        'import_date',
        'quantity',
        'price',
    ];

    public function importReceipts()
    {
        return $this->belongsTo(ImportReceipts::class, 'import_receipt_id', 'id');
    }

    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
