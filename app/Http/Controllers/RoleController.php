<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminRolesView;
use App\Models\Position;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = AdminRolesView::get();
        return view('users.role.index', compact('roles'));
    }

    public function create()
    {
        return view('users.role.create');
    }

    public function store(RoleRequest $request)
    {
        $role['name'] = $request->name;
        return !!AdminRole::create($role);
    }

    public function edit(AdminRole $role)
    {
        return view('users.role.edit', compact('role'));
    }

    public function update(RoleRequest $request, AdminRole $role)
    {
        $role->name = $request->name;
        return !!$role->save();
    }

    public function destroy(AdminRole $role)
    {
        if (DB::table("admin_role_user")->where('role_id', $role->id)->exists() || DB::table("admin_permission_role")->where('role_id', $role->id)->exists()) {
            return "权限使用中,无法删除";
        }
        return !!$role->delete();
    }

    public function permission(AdminRole $role)
    {
        $permissions = AdminPermission::where('level', 1)->orderBy('sort')->get();
        $myPermissions = $role->permissions;
        $positions = Position::where('level', 1)->orderBy('sort')->get();
        $myPositions = $role->positions;
        return view('users.role.permission', compact('role', 'permissions', 'myPermissions', 'positions', 'myPositions',));
    }

    public function storePermission(AdminRole $role)
    {
        $permissions = AdminPermission::find(request('permission'));
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
            DB::table('admin_permission_role')->where('role_id', $role->id)->delete();
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
            DB::table('admin_role_position')->where('role_id', $role->id)->delete();
        }
        return true;
    }
}
