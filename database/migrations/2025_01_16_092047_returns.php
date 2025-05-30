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
        //
        Schema::create('returns', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('invoice_id')->unsigned();
            $table->dateTime('return_date');
            $table->string('reason', 255)->nullable();
            $table->boolean('status')->default(0);
            $table->decimal('total_refund', 15, 2)->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
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
        Schema::dropIfExists('returns');
    }
};
