<?php

namespace Modules\Permission\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PermissionController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        $levels = $user->role->levels;
        $routes = [];
        foreach ($levels as $level) {
            foreach ($level->routes as $route) {
                $routes[] = $route;

            }
        }

        return view('permission::access-denied', compact('routes'));
    }
}
