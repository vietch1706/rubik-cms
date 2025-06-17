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
        Schema::create('roles_permissions', function (Blueprint $table) {
            $table->tinyInteger('role_id')->unsigned();
            $table->tinyInteger('permission_id')->unsigned();
            $table->foreign('permission_id')
                ->references('id')
                ->on('permission_entities')
                ->onDelete('cascade');
            $table->foreign('role_id')
                ->references('id')
                ->on('permission_roles')
                ->onDelete('cascade');
            $table->primary(['role_id', 'permission_id']);
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
        Schema::dropIfExists('roles_permissions');
        Schema::table('roles_permissions', function (Blueprint $table) {
            $table->dropForeign(['permission_id', 'role_id']);
        });
    }
};
