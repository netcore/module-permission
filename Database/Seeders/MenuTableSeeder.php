<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\Menu;
use Modules\Admin\Models\MenuItem;
use Netcore\Translator\Helpers\TransHelper;

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

        foreach ($menus as $key => $items) {
            $menu = Menu::firstOrCreate([
                'key' => $key
            ]);

            $translations = [];
            foreach (TransHelper::getAllLanguages() as $language) {
                $translations[$language->iso_code] = [
                    'name' => ucwords(preg_replace(array('/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'), ' $0', $key))
                ];
            }
            $menu->updateTranslations($translations);

            foreach ($items as $item) {
                $row = $menu->items()->firstOrCreate(array_except($item, ['name', 'value', 'parameters', 'children']));

                $translations = [];
                foreach (TransHelper::getAllLanguages() as $language) {
                    $translations[$language->iso_code] = [
                        'name'       => $item['name'],
                        'value'      => $item['value'],
                        'parameters' => $item['parameters']
                    ];
                }
                $row->updateTranslations($translations);

                foreach ($item['children'] as $child) {
                    $child['menu_id'] = $menu->id;

                    $c = $row->children()->firstOrCreate(array_except($child, ['name', 'value', 'parameters']));
                    $translations = [];
                    foreach (TransHelper::getAllLanguages() as $language) {
                        $translations[$language->iso_code] = [
                            'name'       => $child['name'],
                            'value'      => $child['value'],
                            'parameters' => $child['parameters']
                        ];
                    }
                    $c->updateTranslations($translations);
                }
            }
        }
    }
}
