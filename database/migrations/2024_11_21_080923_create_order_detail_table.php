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
        Schema::create('order_detail', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('order_id')->unsigned()
                ->comment('The order to which these details belong.');
            $table->integer('product_id')->unsigned()
                ->comment('The product being purchased in this order detail.');
            $table->decimal('price', 15, 2)->unsigned()
                ->comment('The agreed price per unit in purchase order .Unit of money is thousands');
            $table->boolean('status')->default(0);
            $table->smallInteger('quantity')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('order_id')
                ->references('id')
                ->on('order')
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
        Schema::dropIfExists('order_details');
    }
};
