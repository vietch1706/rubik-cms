<?php

namespace App\Schema;

use App\Models\Transactions\ImportReceipts;
use App\Models\Users\Users;

class ImportReceiptSchema
{
    private ImportReceipts $importReceipts;

    public function __construct(
        ImportReceipts $importReceipt,
    )
    {
        $this->importReceipts = $importReceipt;
    }

    public function convertData()
    {
        $employeeFullname = Users::select('id', 'first_name', 'last_name')
            ->where('id', $this->importReceipts->user_id)
            ->get(['id', 'first_name', 'last_name'])
            ->pluck('fullName', 'id')
            ->toArray();
        return [
            'id' => $this->importReceipts->id,
            'order_number' => $this->importReceipts->order->order_number,
            'employee' => $employeeFullname,
            'date' => $this->importReceipts->date,
            'status' => $this->importReceipts->status,
            'created_at' => $this->importReceipts->created_at,
            'updated_at' => $this->importReceipts->updated_at,
        ];
    }
}
