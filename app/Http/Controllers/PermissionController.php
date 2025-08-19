<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    public function index()
    {
        return view('users.permission.index');
    }

    public function list(Request $request)
    {
        return Permission::getList($request);
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
        $arr['sort_str'] = isset($parent->sort_str) ? $parent->sort_str . ',' . $arr['sort'] : $arr['sort'];
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
        return !!$permission->save();
    }

    public function destroy($permission)
    {
        $ids = explode(',', $permission);
        $result = Permission::whereIn('id', $ids)
            ->where(function ($query) {
                $query->has('children')
                    ->orHas('roles');
            })
            ->pluck('description')
            ->implode(',');
        if ($result) {
            return $result . '使用中,无法删除';
        } else {
            return !!Permission::whereIn('id', $ids)->delete();
        }
    }

    public function move(Permission $permission, $type)
    {
        return $this->moveUpDown($permission, $type);
    }
}
