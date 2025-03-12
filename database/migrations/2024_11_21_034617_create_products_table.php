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
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->string('sku', 10)->unique();
            $table->date('release_date');
            $table->smallInteger('weight')->unsigned();
            $table->boolean('magnetic')->default(false);
            $table->decimal('price', 15, 2)->unsigned()->nullable()
                ->comment('Unit of money is thousands');
            $table->smallInteger('box_weight')->unsigned();
            $table->smallInteger('quantity')->unsigned();
            $table->boolean('status')->default(true);
            $table->string('image', 100)->nullable();
            $table->softDeletes();
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
