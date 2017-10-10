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
        $role = \Modules\Permission\Models\Role::whereName('Admin')->first();

        $user->role_id = $role->id;
        $user->save();
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
        $role = \Modules\Permission\Models\Role::whereName('User')->first();

        $user->role_id = $role->id;
        $user->save();
    }
}
