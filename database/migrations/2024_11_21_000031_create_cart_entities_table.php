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
        Schema::create('cart_entities', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('customer_id')
                ->unsigned()
                ->comment('Customer who own this cart.');
            $table->json('data')
                ->comment('Item on the cart save on this using JSON');
            $table->timestamps();
            $table->foreign('customer_id')
                ->references('id')
                ->on('user_customers')
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
        Schema::table('cart_entities', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
        });
        Schema::dropIfExists('cart_entities');
    }
};
