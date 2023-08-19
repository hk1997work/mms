<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use App\Models\PermissionsView;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{

    public function index()
    {
        return view('users.permission.index');
    }

    public function create()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        return view('users.permission.create', compact('id'));
    }

    public function store(PermissionRequest $request)
    {
        $parent = Permission::find($request->pid);
        $arr['name'] = $request->name;
        $arr['description'] = $request->description;
        $arr['pid'] = $request->pid;
        $arr['level'] = isset($parent->level) ? $parent->level + 1 : 1;
        $arr['sort'] = Permission::max('sort') + 1;
        $arr['icon'] = $request->icon;
        return !!Permission::create($arr);
    }

    public function edit(Permission $permission)
    {
        return view('users.permission.edit', compact('permission'));
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $permission->name = $request->name;
        $permission->description = $request->description;
        $permission->icon = $request->icon;
        return !!$permission->save();
    }

    public function destroy(Permission $permission)
    {
        if (DB::table("permission_role")->where('permission_id', $permission->id)->exists() || Permission::where('pid', $permission->id)->exists()) {
            return "权限{$permission->description}使用中,无法删除";
        }
        return !!$permission->delete();
    }

    public function move(Permission $permission, $type)
    {
        if ($type) {
            $result = Permission::where('pid', "$permission->pid")->where('sort', '<', $permission->sort)->max('sort');
        } else {
            $result = Permission::where('pid', "$permission->pid")->where('sort', '>', $permission->sort)->min('sort');
        }
        if ($result) {
            $permission_exchange = Permission::where('sort', $result)->first();
            $permission_exchange->sort = $permission->sort;
            $permission->sort = $result;
            return !!$permission->save() && !!$permission_exchange->save();
        } else return false;
    }
}
