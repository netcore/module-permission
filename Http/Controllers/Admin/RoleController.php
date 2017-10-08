<?php

namespace Modules\Permission\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Permission\Http\Requests\CreateRoleRequest;
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
     * Store a newly created role in database.
     * @param CreateRoleRequest $request
     * @return array
     */
    public function store(CreateRoleRequest $request)
    {
        $request->merge([
            'is_active' => $request->get('is_active') ? 1 : 0
        ]);

        $role = Role::create($request->only(['name', 'is_active']));

        $role->levels()->sync($request->get('levels', []));
        $role->save();

        return [
            'message'  => 'Role has been succesfully created',
            'redirect' => route('admin::permission.roles.index')
        ];
    }

    /**
     * Update the specified role in storage.
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

        return [
            'message'  => 'Roles has been successfully modified!',
        ];

    }

    /**
     * Remove the specified role from storage.
     *
     * @param Role $role
     * @return array
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return ['state' => 'success'];
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
}
