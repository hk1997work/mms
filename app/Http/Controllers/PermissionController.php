<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use App\Models\PermissionsView;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{

    public function index()
    {
        return view('users.permission.index');
    }

    public function list(Request $request)
    {
        return DataTables::of(PermissionsView::query())
            ->editColumn('description1', function ($data) {
                return $this->toLevel($data, 1, 'description', 'permission', 'warning', $data->role);
            })
            ->editColumn('description2', function ($data) {
                return $this->toLevel($data, 2, 'description', 'permission', 'success', $data->role);
            })
            ->editColumn('description3', function ($data) {
                return $this->toLevel($data, 3, 'description', 'permission', 'info', $data->role);

            })
            ->editColumn('role', function ($data) {
                return $this->toBadges($data->role, 'secondary');
            })
            ->filter(function ($query) use ($request) {
                $this->toSearch($query, $request, ['description1', 'description2', 'description3', 'name', 'role']);
            })
            ->order(function ($query) use ($request) {
                $this->toOrder($query, $request);
            })
            ->removeColumn('level')
            ->rawColumns([1, 2, 3, 5])
            ->make(false);
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
        return $this->delete(Permission::class, $permission, ['children', 'roles'], 'description');
    }

    public function move(Permission $permission, $type)
    {
        return $this->moveUpDown($permission, $type);
    }
}
