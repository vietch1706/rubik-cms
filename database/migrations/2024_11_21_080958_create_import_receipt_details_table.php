<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_receipt_details', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('import_receipt_id')->unsigned()
                ->comment('Represents the import receipt)');
            $table->integer('product_id')->unsigned()
                ->comment('The imported product');
            $table->date('import_date');
            $table->integer('import_price')
                ->comment('Unit of money is million')->unsigned();
            $table->tinyInteger('import_quantity');
            $table->boolean('status')->default(0);
            $table->foreign('import_receipt_id')
                ->references('id')
                ->on('import_receipts')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_receipt_details');
    }
};
