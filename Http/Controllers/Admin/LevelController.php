<?php

namespace Modules\Permission\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Modules\Permission\Http\Requests\LevelRequest;
use Modules\Permission\Models\Level;
use Modules\Permission\Models\LevelRoute;

class LevelController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $levels = Level::get();

        $routes = [];
        $routes += [
            '*.index'      => '*.index',
            '*.pagination' => '*.pagination',
            '*.create'     => '*.create',
            '*.store'      => '*.store',
            '*.edit'       => '*.edit',
            '*.update'     => '*.update',
            '*.destroy'    => '*.destroy',
        ];

        foreach (Route::getRoutes() as $route) {
            if ($route->getName()) {
                $exploded = explode('.', $route->getName());
                if (isset($exploded[0])) {
                    if ($exploded[0] == 'debugbar') {
                        continue;
                    }
                    $routes[$exploded[0] . '.*'] = $exploded[0] . '.*';
                }
            }
        }

        foreach (Route::getRoutes() as $route) {
            if ($route->getName()) {
                $exploded = explode('.', $route->getName());
                if (isset($exploded[0]) && $exploded[0] == 'debugbar') {
                    continue;
                }
                $routes[$route->getName()] = $route->getName();

            }
        }

        $routes = collect($routes);

        return view('permission::levels.index', compact('levels', 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('permission::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Modify the specified resource in storage.
     * @param LevelRequest $request
     * @return array
     */
    public function modify(LevelRequest $request)
    {
        $level = Level::find($request->get('level_id'));
        $response = [];
        if (!$level) {
            $level = Level::create(['name' => $request->get('name')]);
            $response['newLevel'] = true;
        } else {
            $response['newLevel'] = false;
        }

        $routes = $request->get('routes', []);
        $urls = $request->get('urls', []);

        foreach ($routes as $route) {
            $level->routes()->firstOrCreate(['route' => $route]);
        }
        $level->routes()->where('uri', null)->whereNotIn('route', $routes)->delete();

        foreach ($urls as $url) {
            $level->routes()->firstOrCreate(['uri' => $url]);
        }
        $level->routes()->where('route', null)->whereNotIn('uri', $urls)->delete();

        $level->name = $request->get('name', '');
        $level->save();

        $levels = Level::with('routes')->get();
        $response += [
            'data'    => $level,
            'routes'  => $level->routes()->where('route', '!=', null)->pluck('route')->toArray(),
            'urls'    => $level->routes()->where('uri', '!=', null)->pluck('uri')->toArray(),
            'message' => 'Level has been successfully updated!',
            'allLevels' => $levels->toJson()
        ];

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     * @param Level $level
     * @return array
     */
    public function destroy(Level $level)
    {
        $level->delete();

        return [
            'state' => 'success'
        ];
    }

    /**
     * Update field in "Roles" table using x-editable
     *
     * @param Request $request
     * @return array
     */
    public function updateRoute(Request $request)
    {
        $route = LevelRoute::find($request->get('pk', null));
        if (!$route) {
            abort(404);
        }

        $field = $request->get('name', null);
        $value = $request->get('value', null);

        $route->{$field} = $value;
        $route->save();

        return [
            'type' => 'success'
        ];
    }
}
