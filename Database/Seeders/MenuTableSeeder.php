<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\Menu;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $menus = [
            'leftAdminMenu' => [
                [
                    'name'   => 'Roles',
                    'icon'   => 'ion-briefcase',
                    'type'   => 'route',
                    'value'  => 'permission::role.index',
                    'module' => 'Permission'
                ],
                [
                    'name'   => 'Permissions',
                    'icon'   => 'ion-android-unlock',
                    'type'   => 'route',
                    'value'  => 'permission::permission.index',
                    'module' => 'Permission'
                ],
            ]
        ];

        foreach( $menus as $name => $items ) {
            $menu = Menu::firstOrCreate([
                'name' => $name
            ]);

            foreach( $items as $item ){
                $i = $menu->items()->firstOrCreate($item);
                $i->is_active = 1;
                $i->save();
            }
        }
    }
}