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
        Schema::create('product_subs', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('name', 100);
            $table->decimal('price', 15, 2)->unsigned()->nullable();
            $table->boolean('is_discount')->default(0);
            $table->float('discount', 10, 2)->default(0);
            $table->smallInteger('quantity')->unsigned();
            $table->string('image');
            $table->softDeletes();
            $table->timestamps();
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
        Schema::table('product_subs', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        Schema::dropIfExists('product_subs');
    }
};
