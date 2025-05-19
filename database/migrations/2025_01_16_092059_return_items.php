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
        Schema::create('return_item', function (Blueprint $table) {
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
                ->on('return')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')
                ->on('product')
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
        //
    }
};
