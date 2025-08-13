<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Position;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return view('users.role.index');
    }

    public function list(Request $request)
    {
        return Role::getList($request);
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
        $ids = explode(',', $role);
        $result = Role::whereIn('id', $ids)
            ->where(function ($query) {
                $query->has('users')
                    ->orHas('permissions')
                    ->orHas('positions');
            })
            ->pluck('name')
            ->implode(',');
        if ($result) {
            return $result . '使用中,无法删除';
        } else {
            return !!Role::whereIn('id', explode(',', $role))->delete();
        }
    }

    public function permission($role)
    {
        $permissions = Permission::where('level', 1)->orderBy('sort')->get();
        $positions = Position::where('level', 1)->orderBy('sort')->get();
        $myPermissions = strpos($role, ',') ? '' : Role::find($role)->permissions;
        $myPositions = strpos($role, ',') ? '' : Role::find($role)->positions;
        return view('users.role.permission', compact('permissions', 'myPermissions', 'positions', 'myPositions',));
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
