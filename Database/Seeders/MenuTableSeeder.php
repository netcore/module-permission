<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\Menu;
use Modules\Admin\Models\MenuItem;

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
                    'name'       => 'Permissions',
                    'icon'       => 'ion-android-unlock',
                    'type'       => 'url',
                    'value'      => '#',
                    'module'     => 'Permission',
                    'is_active'  => 1,
                    'parameters' => json_encode([]),
                    'children'   => [
                        [
                            'name'            => 'Roles',
                            'type'            => 'route',
                            'value'           => 'admin::permission.roles.index',
                            'active_resolver' => 'admin::permission.roles.*',
                            'module'          => 'Permission',
                            'is_active'       => 1,
                            'parameters'      => json_encode([])
                        ],
                        [
                            'name'            => 'Levels',
                            'type'            => 'route',
                            'value'           => 'admin::permission.levels.index',
                            'active_resolver' => 'admin::permission.levels.*',
                            'module'          => 'Permission',
                            'is_active'       => 1,
                            'parameters'      => json_encode([])
                        ],
                    ]
                ],
            ]
        ];

        foreach ($menus as $name => $items) {
            $menu = Menu::firstOrCreate([
                'name' => $name
            ]);


            foreach ($items as $item) {
                $item['menu_id'] = $menu->id;
                $parentItem = MenuItem::firstOrCreate(array_except($item, 'children'));

                foreach($item['children'] as $child) {
                    $child['parent_id'] = $parentItem->id;
                    $child['menu_id'] = $menu->id;

                    MenuItem::firstOrCreate($child);
                }
            }
        }
    }
}
