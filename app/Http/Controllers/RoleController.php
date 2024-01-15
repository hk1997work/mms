<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolesView;
use App\Models\Position;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        return view('users.role.index');
    }

    public function list()
    {
        $data = RolesView::select('id', 'name', 'user', 'permission', 'position',)->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' id='$value[id]'>
                        <label for='$value[id]'></label>
                    </div>";
            if ($value['user'] == '' || $value['permission'] == '' || $value['position'] == '') {
                $data[$key]['name'] = "<div class='text-danger'>$value[name]</div>";
            }
        }
        return response()->json(['data' => array_map('array_values', $data)]);
    }

    public function create()
    {
        return view('users.role.create');
    }

    public function store(RoleRequest $request)
    {
        $role['name'] = $request->name;
        return !!Role::create($role);
    }

    public function edit(Role $role)
    {
        return view('users.role.edit', compact('role'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role->name = $request->name;
        return !!$role->save();
    }

    public function destroy($role)
    {
        $user = DB::table("role_user")->selectRaw('GROUP_CONCAT(role_id) AS str')->whereIn('role_id', explode(',', $role))->first();
        $permission = DB::table("permission_role")->selectRaw('GROUP_CONCAT(role_id) AS str')->whereIn('role_id', explode(',', $role))->first();
        $position = DB::table("position_role")->selectRaw('GROUP_CONCAT(role_id) AS str')->whereIn('role_id', explode(',', $role))->first();
        if ($user->str || $permission->str || $position->str) {
            $id = implode(',', [$user->str, $permission->str, $position->str]);
            $result = Role::selectRaw('GROUP_CONCAT(name) AS name')->whereIn('id', array_unique(explode(',', $id)))->first();
            return $result->name . '使用中,无法删除';
        } else {
            return !!Role::whereIn('id', explode(',', $role))->delete();
        }
    }

    public function permission($role)
    {
        $permissions = Permission::orderBy('sort')->get();
        $positions = Position::orderBy('sort')->get();
        $myPermissions = strpos($role, ',') ? '' : Role::find($role)->permissions;
        $myPositions = strpos($role, ',') ? '' : Role::find($role)->positions;
        return view('users.role.permission', compact('role', 'permissions', 'myPermissions', 'positions', 'myPositions',));
    }

    public function storePermission($role)
    {
        $permissions = Permission::find(request('permission'));
        $positions = Position::find(request('position'));
        $roles = Role::find(explode(',', $role));
        foreach ($roles as $r) {
            $r->permissions()->sync($permissions);
            $r->positions()->sync($positions);
        }
        return true;
    }
}
