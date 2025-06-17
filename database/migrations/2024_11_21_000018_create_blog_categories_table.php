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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->tinyInteger('parent_id')
                ->unsigned()
                ->nullable()
                ->comment('References the parent category ID. Null indicates a root category.');
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('parent_id')
                ->references('id')
                ->on('blog_categories')
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
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });
        Schema::dropIfExists('blog_categories');
    }
};
