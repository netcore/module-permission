<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetcorePermissionLevelRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_permission__level_role', function (Blueprint $table) {
            $table->unsignedInteger('level_id');
            $table->unsignedInteger('role_id');

            $table->foreign('level_id')->references('id')->on('netcore_permission__levels')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('netcore_permission__roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('netcore_permission__level_role');
    }
}
