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
        //
        Schema::create('invoice_return_details', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('return_id')->unsigned();
            $table->integer('product_id')->unsigned()
                ->comment('References the product being purchased in this invoice detail.');
            $table->decimal('price', 15, 2)->unsigned()
                ->comment('The actual price per unit at the time of receiving the products .Unit of money is thousands');
            $table->smallInteger('quantity')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('return_id')
                ->references('id')
                ->on('invoice_returns')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')
                ->on('product_entities')
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
        Schema::dropIfExists('invoice_return_details');
    }
};
