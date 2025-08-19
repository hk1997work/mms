<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    //当前角色所有用户
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id')->withPivot(['user_id', 'role_id'])->orderBy('user_id');
    }

    //当前角色所有权限
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id')->withPivot(['permission_id', 'role_id'])->orderBy('sort');
    }

    //当前角色所有岗位
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'position_role', 'role_id', 'position_id')->withPivot(['position_id', 'role_id'])->orderBy('sort');
    }

    public function getList($request)
    {
        $permissions = Permission::where('level', 1)->orderBy('sort')->with('children')->get();
        $positions = Position::where('level', 1)->orderBy('sort')->with('children')->get();
        $query = self::select('id', 'name')
            ->with('users:username', 'permissions:id', 'positions:id');

        return DataTables::of($query)
            ->addColumn('users', function ($data) {
                return $data->users->pluck('username')->map(function ($user) {
                    return "<span class='badge bg-secondary-subtle border border-secondary-subtle text-secondary-emphasis'>" . e($user) . "</span>";
                })->implode(' ');
            })
            ->addColumn('permissions', function ($data) use ($permissions) {
                $ids = $data->permissions->pluck('id');
                return $permissions->map(function ($permission) use ($ids) {
                    if ($ids->contains($permission->id)) {
                        $html = '<a class="badge btn btn-outline-secondary text-secondary-emphasis" href="#" data-bs-html="true" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="top" data-bs-content="';
                        $html .= $permission->children->map(function ($permission) use ($ids) {
                            $html = $ids->contains($permission->id)
                                ? "<span class='badge bg-primary-subtle border border-primary-subtle text-primary-emphasis my-1'>" . e($permission->description) . "</span> "
                                : "<span class='badge bg-light-subtle border border-light-subtle text-light-emphasis my-1'>" . e($permission->description) . "</span> ";
                            $html .= $permission->children->map(function ($permission) use ($ids) {
                                return $ids->contains($permission->id)
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
                $ids = $data->positions->pluck('id');
                return $positions->map(function ($position) use ($ids) {
                    if ($ids->contains($position->id)) {
                        $html = '<a class="badge btn btn-outline-secondary text-secondary-emphasis" href="#" data-bs-html="true" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="top" data-bs-content="';
                        $html .= $position->children->map(function ($position) use ($ids) {
                            return $ids->contains($position->id)
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
            ->editColumn('name', function ($data) {
                return count($data->users) && count($data->permissions) && count($data->positions)
                    ? e($data->name)
                    : "<span class='badge bg-danger-subtle border border-danger-subtle text-danger-emphasis'>" . e($data->name) . "</span>";
            })
            ->filter(function ($query) use ($request) {
                if (!empty($search = $request->search['value'])) {
                    $terms = explode(' ', $search);
                    $query->where(function ($q) use ($terms) {
                        foreach ($terms as $term) {
                            $q->where(function ($innerQ) use ($term) {
                                $innerQ->where('name', 'like', "%$term%")
                                    ->orWhereHas('users', function ($innerQ) use ($term) {
                                        $innerQ->where('username', 'like', "%$term%");
                                    })
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
}
