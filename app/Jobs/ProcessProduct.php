<?php

namespace App\Jobs;

use App\Models\Catalogs\Products;
use App\Models\Transactions\ImportReceipts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ImportReceipts $importReceipt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct
    (
        ImportReceipts $importReceipt
    )
    {
        //
        $this->importReceipt = $importReceipt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $importReceiptDetails = $this->importReceipt->details;
        foreach ($importReceiptDetails as $importReceiptDetail) {
            $product = Products::find($importReceiptDetail->product_id);
            $product->quantity = $product->quantity + $importReceiptDetail->quantity;
            $product->price = $importReceiptDetail->price;
            $product->save();
        }
    }
}
