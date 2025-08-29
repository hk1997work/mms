<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Position;
use App\Models\RolesView;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        return view('users.role.index');
    }

    public function list(Request $request)
    {
        $permissions = Permission::where('level', 1)->orderBy('sort')->with('children')->get();
        $positions = Position::where('level', 1)->orderBy('sort')->with('children')->get();
        return DataTables::of(RolesView::query())
            ->editColumn('name', function ($data) {
                return $this->toValidate($data->name, $data->user && $data->permission && $data->position);
            })
            ->editColumn('user', function ($data) {
                return $this->toBadges($data->user, 'secondary');
            })
            ->editColumn('permission', function ($data) use ($permissions) {
                return $this->toManyBadges($data->permission_id, $permissions, 'description');
            })
            ->editColumn('position', function ($data) use ($positions) {
                return $this->toManyBadges($data->position_id, $positions, 'name');
            })
            ->filter(function ($query) use ($request) {
                $this->toSearch($query, $request, ['name', 'user', 'permission', 'position']);
            })
            ->order(function ($query) use ($request) {
                $this->toOrder($query, $request, ['id', 'name', 'user', 'permission', 'position']);
            })
            ->setTotalRecords(Role::count())
            ->removeColumn('permission_id', 'position_id')
            ->rawColumns([1, 2, 3, 4])
            ->make(false);
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
        return $this->delete(Role::class, $role, ['users', 'permissions', 'positions'], 'name');
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
