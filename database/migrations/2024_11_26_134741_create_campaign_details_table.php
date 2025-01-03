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
        Schema::create('campaign_details', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('campaign_id')->unsigned();
            $table->boolean('type')->default(0)
                ->comment('0: discount; 1: buy one free one');
            $table->integer('product_id')
                ->unsigned()
                ->comment('Main product in the campaign.');
            $table->decimal('discount_percent', 15, 2)
                ->comment('Null if the campaign is bundle campaign')
                ->nullable();
            $table->integer('bundle_product_id')
                ->unsigned()
                ->nullable()
                ->comment('The product given for free when purchasing the main product');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
            $table->foreign('campaign_id')
                ->references('id')
                ->on('campaigns')
                ->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_details');
    }
};
