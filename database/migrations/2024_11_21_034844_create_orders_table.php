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
            $table->integer('employee_id')->unsigned()
                ->comment('References to current employee who created the order.');
            $table->string('order_no', 10)->unique();
            $table->dateTime('date');
            $table->boolean('status')->default(0);
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('distributor_id')
                ->references('id')
                ->on('distributors')
                ->onDelete('cascade');
            $table->foreign('employee_id')
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
        Schema::dropIfExists('orders');
    }
};
