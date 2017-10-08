<?php

Route::group([
    'prefix'     => 'admin/permission',
    'as'         => 'admin::permission.',
    'middleware' => ['web', 'auth.admin'],
    'namespace'  => 'Modules\Permission\Http\Controllers\Admin'
], function () {
    Route::resource('roles', 'RoleController', ['except' => [
        'update'
    ]]);
    Route::resource('levels', 'LevelController');

    Route::put('roles/update', [
        'uses' => 'RoleController@update',
        'as'   => 'roles.update'
    ]);

    Route::post('roles/name/update', [
        'uses' => 'RoleController@updateField',
        'as'   => 'roles.field.update'
    ]);
});
