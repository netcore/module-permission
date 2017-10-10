<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Permission\Models\Level;
use Modules\Permission\Models\Role;

class LevelsTableSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $levels = [
            [
                'name' => 'Read',
                'routes' => [
                    ['route' => '*.index'],
                    ['route' => '*.pagination'],
                ]
            ],
            [
                'name' => 'Create',
                'routes' => [
                    ['route' => '*.create'],
                    ['route' => '*.store'],
                ]
            ],
            [
                'name' => 'Edit',
                'routes' => [
                    ['route' => '*.edit'],
                    ['route' => '*.update'],
                ]
            ],
            [
                'name' => 'Delete',
                'routes' => [
                    ['route' => '*.destroy'],
                ]
            ],
            ['name' => 'Custom'],
        ];
        $ids = [];
        foreach($levels as $level) {
            $levelModel = Level::firstOrCreate(array_only($level, 'name'));

            if(isset($level['routes'])) {
                foreach($level['routes'] as $route) {
                    $levelModel->routes()->firstOrCreate($route);
                }
            }
            $ids[] = $levelModel->id;
        }

        $role = Role::whereName('Admin')->first();
        $role->levels()->sync($ids);
    }
}
