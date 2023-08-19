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
        $roles = RolesView::get();
        return view('users.role.index', compact('roles'));
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

    public function destroy(Role $role)
    {
        if (DB::table("role_user")->where('role_id', $role->id)->exists() || DB::table("permission_role")->where('role_id', $role->id)->exists()) {
            return "角色{$role->name}使用中,无法删除";
        }
        return !!$role->delete();
    }

    public function permission(Role $role)
    {
        $permissions = Permission::orderBy('sort')->get();
        $myPermissions = $role->permissions;
        $positions = Position::orderBy('sort')->get();
        $myPositions = $role->positions;
        return view('users.role.permission', compact('role', 'permissions', 'myPermissions', 'positions', 'myPositions',));
    }

    public function storePermission(Role $role)
    {
        $permissions = Permission::find(request('permission'));
        if (isset($permissions)) {
            $myPermissions = $role->permissions;
            $addPermissions = $permissions->diff($myPermissions);
            foreach ($addPermissions as $permission) {
                $role->addPermission($permission);
            }
            $deletePermissions = $myPermissions->diff($permissions);
            foreach ($deletePermissions as $permission) {
                $role->deletePermission($permission);
            }
        } else {
            DB::table('permission_role')->where('role_id', $role->id)->delete();
        }
        $positions = Position::find(request('position'));
        if (isset($positions)) {
            $myPositions = $role->positions;
            $addPositions = $positions->diff($myPositions);
            foreach ($addPositions as $position) {
                $role->addPosition($position);
            }
            $deletePositions = $myPositions->diff($positions);
            foreach ($deletePositions as $position) {
                $role->deletePosition($position);
            }
        } else {
            DB::table('role_position')->where('role_id', $role->id)->delete();
        }
        return true;
    }
}
