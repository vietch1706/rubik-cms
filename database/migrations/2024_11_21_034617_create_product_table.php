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
        Schema::create('product', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->string('sku', 10)->unique();
            $table->date('release_date');
            $table->boolean('type')->default(0);
            $table->longText('description')->nullable();
            $table->boolean('magnetic')->default(false);
            $table->decimal('price', 15, 2)->unsigned()->nullable()
                ->comment('Unit of money is thousands');
            $table->boolean('is_discount')->default(0);
            $table->float('discount', 10, 2)->default(0);
            $table->float('weight', 10, 2)->default(0)->unsigned();
            $table->float('box_weight', 10, 2)->unsigned();
            $table->smallInteger('quantity')->unsigned();
            $table->boolean('status')->default(true);
            $table->string('image', 100)->nullable();
            $table->json('gallery')->nullable();
            $table->tinyInteger('category_id')->unsigned();
            $table->tinyInteger('brand_id')->unsigned();
            $table->integer('purchase_count')->unsigned()->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('category_id')
                ->references('id')
                ->on('category')
                ->onDelete('cascade');
            $table->foreign('brand_id')
                ->references('id')
                ->on('brand')
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
