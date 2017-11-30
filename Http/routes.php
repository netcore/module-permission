<?php

Route::group([
    'prefix'     => 'admin/permission',
    'as'         => 'admin::permission.',
    'middleware' => ['web', 'auth.admin'],
    'namespace'  => 'Modules\Permission\Http\Controllers\Admin'
], function () {
    Route::resource('roles', 'RoleController', [
        'only' => [
            'index',
            'destroy',
            'store'
        ]
    ]);

    Route::resource('levels', 'LevelController', [
        'only' => [
            'index',
            'destroy'
        ]
    ]);

    Route::put('roles/update', [
        'uses' => 'RoleController@update',
        'as'   => 'roles.update'
    ]);

    Route::post('roles/name/update', [
        'uses' => 'RoleController@updateField',
        'as'   => 'roles.field.update'
    ]);

    Route::post('routes/update', [
        'uses' => 'LevelController@updateRoute',
        'as'   => 'levels.route.update'
    ]);

    Route::post('levels/update', [
        'uses' => 'LevelController@modify',
        'as'   => 'levels.modify'
    ]);
});
