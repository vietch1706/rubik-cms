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
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->tinyInteger('distributor_id')->unsigned()
                ->comment('References the distributor who supplies the goods or products.');
            $table->integer('user_id')->unsigned()
                ->comment('References to current employee who created the order.');
            $table->string('order_number', 10)->unique();
            $table->dateTime('date');
            $table->boolean('status')->default(0);
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('distributor_id')
                ->references('id')
                ->on('distributors')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('orders');
    }
};
