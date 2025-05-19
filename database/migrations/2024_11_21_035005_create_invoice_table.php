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
        Schema::create('invoice', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('employee_id')->unsigned()
                ->comment('References the employee who created the invoice.');
            $table->integer('customer_id')->unsigned()
                ->comment('References the customer who is billed in the invoice.');
            $table->dateTime('date');
            $table->boolean('status')->default(0);
            $table->string('note')->nullable();
            $table->json('campaign')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('customer_id')
                ->references('id')
                ->on('customer')
                ->onDelete('cascade');
            $table->foreign('employee_id')
                ->references('id')
                ->on('employee')
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
        Schema::dropIfExists('invoices');
    }
};
