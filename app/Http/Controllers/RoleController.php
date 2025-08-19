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
        return DataTables::of(RolesView::query()->with('positions', 'permissions'))
            ->editColumn('name', function ($data) {
                return $data->users && $data->permissions && $data->positions
                    ? e($data->name)
                    : "<span class='badge bg-danger-subtle border border-danger-subtle text-danger-emphasis'>" . e($data->name) . "</span>";
            })
            ->editColumn('users', function ($data) {
                $arrays = explode(',', $data->users);
                $badges = [];
                foreach ($arrays as $array) {
                    $badges[] = "<span class='badge bg-secondary-subtle border border-secondary-subtle text-secondary-emphasis'>" . e($array) . "</span>";
                }
                return implode(' ', $badges);
            })
            ->editColumn('permissions', function ($data) use ($permissions) {
                $ids = explode(',', $data->permissions);
                return $permissions->map(function ($permission) use ($ids) {
                    if (in_array($permission->id, $ids)) {
                        $html = '<a class="badge btn btn-outline-secondary text-secondary-emphasis" href="#" data-bs-html="true" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="top" data-bs-content="';
                        $html .= $permission->children->map(function ($permission) use ($ids) {
                            $html = in_array($permission->id, $ids)
                                ? "<span class='badge bg-primary-subtle border border-primary-subtle text-primary-emphasis my-1'>" . e($permission->description) . "</span> "
                                : "<span class='badge bg-light-subtle border border-light-subtle text-light-emphasis my-1'>" . e($permission->description) . "</span> ";
                            $html .= $permission->children->map(function ($permission) use ($ids) {
                                return in_array($permission->id, $ids)
                                    ? "<span class='badge bg-primary-subtle border border-primary-subtle text-primary-emphasis my-1'>" . e($permission->description) . "</span>"
                                    : "<span class='badge bg-light-subtle border border-light-subtle text-light-emphasis my-1'>" . e($permission->description) . "</span>";
                            })->implode('');;
                            return $html;
                        })->implode('<br>');
                        $html .= '"> ' . e($permission->description) . '</a>';
                        return $html;
                    } else {
                        return "<span class='badge btn btn-outline-secondary text-secondary-emphasis invisible'>" . e($permission->description) . "</span>";
                    }
                })->implode(' ');
            })
            ->addColumn('positions', function ($data) use ($positions) {
                $ids = explode(',', $data->positions);
                return $positions->map(function ($position) use ($ids) {
                    if (in_array($position->id, $ids)) {
                        $html = '<a class="badge btn btn-outline-secondary text-secondary-emphasis" href="#" data-bs-html="true" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="top" data-bs-content="';
                        $html .= $position->children->map(function ($position) use ($ids) {
                            return in_array($position->id, $ids)
                                ? "<span class='badge bg-primary-subtle border border-primary-subtle text-primary-emphasis my-1'>" . e($position->name) . "</span> "
                                : "<span class='badge bg-light-subtle border border-light-subtle text-light-emphasis my-1'>" . e($position->name) . "</span> ";
                        })->implode('<br>');
                        $html .= '"> ' . e($position->name) . '</a>';
                        return $html;
                    } else {
                        return "<span class='badge btn btn-outline-secondary text-secondary-emphasis invisible'>" . e($position->name) . "</span>";
                    }
                })->implode(' ');
            })
            ->filter(function ($query) use ($request) {
                if (!empty($search = $request->search['value'])) {
                    $terms = explode(' ', $search);
                    $query->where(function ($q) use ($terms) {
                        foreach ($terms as $term) {
                            $q->where(function ($innerQ) use ($term) {
                                $innerQ->where('name', 'like', "%$term%")
                                    ->orWhere('users', 'like', "%$term%")
                                    ->orWhereHas('permissions', function ($innerQ) use ($term) {
                                        $innerQ->where('description', 'like', "%$term%");
                                    })
                                    ->orWhereHas('positions', function ($innerQ) use ($term) {
                                        $innerQ->where('name', 'like', "%$term%");
                                    });
                            });
                        }
                    });
                }
            })
            ->rawColumns([0, 1, 2, 3, 4])
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
            return !!Role::whereIn('id', $ids)->delete();
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
