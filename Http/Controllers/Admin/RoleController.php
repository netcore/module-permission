<?php

namespace Modules\Permission\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Permission\Models\Level;
use Modules\Permission\Models\Role;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $roles = Role::get();
        $levels = Level::get();

        return view('permission::roles.index', compact('roles', 'levels'));
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
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('permission::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('permission::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        // Sync levels
        $levels = $request->get('levels', []);

        if (count($levels)) {
            foreach ($levels as $role => $levelIds) {
                $role = Role::find($role);
                if ($role) {
                    $role->levels()->sync($levelIds);
                }
            }
        }

        // Update is_active
        $activeFields = $request->get('active', []);
        if (count($activeFields)) {
            Role::whereIn('id', array_keys($activeFields))->update(['is_active' => 1]);
            Role::whereNotIn('id', array_keys($activeFields))->update(['is_active' => 0]);
        }

        return redirect()->back()->withSuccess('Roles has been successfully modified!');
    }

    /**
     * Update field in "Roles" table using x-editable
     *
     * @param Request $request
     * @return array
     */
    public function updateField(Request $request)
    {
        $role = Role::find($request->get('pk', null));
        if (!$role) {
            abort(404);
        }

        $field = $request->get('name', null);
        $value = $request->get('value', null);

        $role->{$field} = $value;
        $role->save();

        return [
            'type' => 'success'
        ];
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
