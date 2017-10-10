<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminUserAdminRole extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userModel = config('auth.providers.users.model');

        $user = $userModel::whereIsAdmin(1)->first();

        if ($user) {
            $user->role_id = 1;
            $user->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $userModel = config('auth.providers.users.model');

        $user = $userModel::whereIsAdmin(1)->first();

        if ($user) {
            $user->role_id = 2;
            $user->save();
        }
    }
}
