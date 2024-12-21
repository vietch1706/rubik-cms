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
        Schema::create('distributors_products', function (Blueprint $table) {
            $table->tinyInteger('distributor_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->decimal('price', 15, 2)->unsigned()->nullable()
                ->comment('Unit of money is thousands');
            $table->softDeletes();
            $table->foreign('distributor_id')
                ->references('id')
                ->on('distributors')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
            $table->primary(['distributor_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distributors_products');
    }
};
