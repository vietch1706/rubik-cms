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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->tinyInteger('category_id')->unsigned();
            $table->tinyInteger('brand_id')->unsigned();
            $table->string('name', 50);
            $table->string('slug', 50)->unique();
            $table->string('sku', 20)->unique();
            $table->date('release_date');
            $table->smallInteger('weight');
            $table->boolean('magnetic')->default(false);
            $table->integer('price')->unsigned()->comment('Unit of money is million');
            $table->smallInteger('box_weight');
            $table->tinyInteger('quantity');
            $table->string('unit', 10);
            $table->string('image', 100)->nullable();
            $table->timestamps();
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
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
        Schema::dropIfExists('products');
    }
};
