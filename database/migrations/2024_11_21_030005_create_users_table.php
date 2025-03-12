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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->tinyInteger('role_id')->unsigned();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->boolean('gender')->default(2);
            $table->string('phone', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('address')->nullable();
            $table->string('avatar')->nullable();
            $table->string('password', 64);
            $table->boolean('is_activated')->default(false);
            $table->timestamp('activated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
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
        Schema::dropIfExists('users');
    }
};
