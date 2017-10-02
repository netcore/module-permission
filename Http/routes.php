<?php
use Modules\Admin\Http\Middleware\Admin\isAdmin;

//admin routes
Route::group(['middleware' => ['web',isAdmin::class], 'prefix' => 'admin/user', 'namespace' => 'Modules\User\Http\Controllers\Admin'], function()
{
    Route::get('/role', [
        'uses' => 'RolesController@index',
        'as'   => 'permission::roles.index'
    ]);

    Route::get('/permission', [
        'uses' => 'PermissionsController@index',
        'as'   => 'permission::permissions.index'
    ]);
});
