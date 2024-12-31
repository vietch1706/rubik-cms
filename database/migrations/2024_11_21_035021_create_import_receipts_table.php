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
        Schema::create('import_receipts', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('order_no', 10)->unique()
                ->comment('Linking to a specific purchase order.');
            $table->integer('user_id')->unsigned()->nullable()
                ->comment('Employee who created the inventory receipt for the order.');
            $table->dateTime('date');
            $table->boolean('status')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('order_no')
                ->references('order_number')
                ->on('orders')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('employees')
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
        Schema::dropIfExists('import_receipts');
    }
};
