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
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('employee_id')->unsigned()
                ->comment('References the employee who authored the blog post.');
            $table->string('title', 100);
            $table->string('slug', 100)
                ->comment('Slug from Title');
            $table->tinyInteger('category_id')->unsigned()
                ->comment('Only take category with Blogs Parent.');
            $table->longText('content');
            $table->string('thumbnail', 100)->nullable();
            $table->dateTime('date');
            $table->timestamps();
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
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
        Schema::dropIfExists('blogs');
    }
};
