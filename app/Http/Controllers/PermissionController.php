<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Models\AdminPermission;
use App\Models\AdminPermissionsView;
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
        $parent = AdminPermission::find($request->pid);
        $arr['name'] = $request->name;
        $arr['description'] = $request->description;
        $arr['pid'] = $request->pid;
        $arr['level'] = isset($parent->level) ? $parent->level + 1 : 1;
        $arr['sort'] = AdminPermission::max('sort') + 1;
        $arr['icon'] = $request->icon;
        return !!AdminPermission::create($arr);
    }

    public function edit(AdminPermission $permission)
    {
        return view('users.permission.edit', compact('permission'));
    }

    public function update(PermissionRequest $request, AdminPermission $permission)
    {
        $permission->name = $request->name;
        $permission->description = $request->description;
        $permission->icon = $request->icon;
        return !!$permission->save();
    }

    public function destroy(AdminPermission $permission)
    {
        if (DB::table("admin_permission_role")->where('permission_id', $permission->id)->exists()) {
            return "权限使用中,无法删除";
        }
        return !!$permission->delete();
    }

    public function move(AdminPermission $permission, $type)
    {
        if ($type) {
            $result = AdminPermission::where('pid', "$permission->pid")->where('sort', '<', $permission->sort)->max('sort');
        } else {
            $result = AdminPermission::where('pid', "$permission->pid")->where('sort', '>', $permission->sort)->min('sort');
        }
        if ($result) {
            $permission_exchange = AdminPermission::where('sort', $result)->first();
            $permission_exchange->sort = $permission->sort;
            $permission->sort = $result;
            return !!$permission->save() && !!$permission_exchange->save();
        } else return false;
    }
}
