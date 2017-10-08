<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetcorePermissionLevelRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_permission__level_routes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('level_id');
            $table->string('route')->nullable();
            $table->string('uri')->nullable();

            $table->foreign('level_id')->references('id')->on('netcore_permission__levels')->onDelete('cascade');
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
        Schema::dropIfExists('netcore_permission__level_routes');
    }
}
