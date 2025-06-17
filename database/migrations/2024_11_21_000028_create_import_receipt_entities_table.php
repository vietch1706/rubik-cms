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
        Schema::create('import_receipt_entities', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('order_no', 10)
                ->comment('Linking to a specific purchase order.');
            $table->integer('employee_id')
                ->unsigned()
                ->nullable()
                ->comment('Employee who created the inventory receipt for the order.');
            $table->dateTime('date');
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('order_no')
                ->references('order_no')
                ->on('order_entities')
                ->onDelete('cascade');
            $table->foreign('employee_id')
                ->references('id')
                ->on('user_employees')
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
        Schema::table('import_receipt_entities', function (Blueprint $table) {
            $table->dropForeign(['order_no', 'employee_id']);
        });
        Schema::dropIfExists('import_receipt_entities');
    }
};
