<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_detail', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('invoice_id')->unsigned()
                ->comment('References the invoice to which these details belong.');
            $table->integer('product_id')->unsigned()
                ->comment('References the product being purchased in this invoice detail.');
            $table->decimal('price', 15, 2)->unsigned()
                ->comment('The actual price per unit at the time of receiving the products .Unit of money is thousands');
            $table->smallInteger('quantity')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_details');
    }
};
