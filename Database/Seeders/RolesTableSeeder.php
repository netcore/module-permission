<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $roles = [
            'Admin',
            'User',
            'Moderator',
            'Spectator',
        ];

        foreach( $roles as $roleName ){
            Role::firstOrCreate([
                'name' => $roleName,
            ]);
        }
    }
}
