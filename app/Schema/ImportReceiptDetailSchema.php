<?php

namespace App\Schema;

use App\Models\Transactions\ImportReceiptDetails;
use App\Models\Transactions\OrderDetails;

class ImportReceiptDetailSchema
{

    private ImportReceiptDetails $importReceiptDetails;

    public function __construct(
        ImportReceiptDetails $importReceiptDetail,
    )
    {
        $this->importReceiptDetails = $importReceiptDetail;
    }

    public function convertData()
    {
        $product = $this->importReceiptDetails->products()->pluck('name', 'id')->toArray();
        return [
            'id' => $this->importReceiptDetails->id,
            'product' => $product,
            'import_date' => $this->importReceiptDetails->import_date,
            'price' => $this->importReceiptDetails->price,
            'quantity' => $this->importReceiptDetails->quantity,
            'created_at' => $this->importReceiptDetails->created_at,
            'updated_at' => $this->importReceiptDetails->updated_at,
        ];
    }
}
