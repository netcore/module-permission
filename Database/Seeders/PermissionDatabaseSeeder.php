<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PermissionDatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(MenuTableSeeder::class);
        if (config('netcore.module-permission.seed_example_data', true)) {
            $this->call(RolesTableSeeder::class);
            $this->call(LevelsTableSeederTableSeeder::class);
        }
    }
}
