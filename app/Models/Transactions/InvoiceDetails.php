<?php

namespace App\Models\Transactions;

use App\Models\Catalogs\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'invoice_details';
    protected $fillable = [
        'import_receipt_id',
        'product_id',
        'price',
        'quantity',
    ];

    public function products()
    {
        return $this->hasMany(Products::class, 'id', 'product_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoices::class, 'invoice_id', 'id');
    }
}
