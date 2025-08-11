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
        $permissions = Permission::where('level', 1)->orderBy('sort')->get();
        $positions = Position::where('level', 2)->orderBy('sort')->get();
        $query = Role::select([
            'id',
            'name',
        ])->with([
            'users:username',
            'permissions:id',
            'positions:id',
        ]);

        return DataTables::of($query)
            ->addColumn('users', function ($data) {
                return $data->users->pluck('username')->map(function ($user) {
                    return '<span class="tag btn-sm tag-primary">' . e($user) . '</span>';
                })->implode(' ');
            })
            ->addColumn('permissions', function ($data) use ($permissions) {
                $ids = $data->permissions->pluck('id');
                return $permissions->map(function ($permission) use ($ids) {
                    if ($ids->contains($permission->id)) {
                        $html = "<a class='tag btn-sm tag-outline-primary' href='#' data-html='true' data-toggle='popover' data-trigger='hover' data-placement='top' data-content='";
                        $html .= $permission->childs->map(function ($permission) use ($ids) {
                            $html = $ids->contains($permission->id)
                                ? '<span class="tag btn-sm tag-primary">' . e($permission->description) . '</span> '
                                : '<span class="tag btn-sm tag-secondary">' . e($permission->description) . '</span> ';
                            $html .= $permission->childs->map(function ($permission) use ($ids) {
                                return $ids->contains($permission->id)
                                    ? '<span class="tag btn-sm tag-primary">' . e($permission->description) . '</span>'
                                    : '<span class="tag btn-sm tag-secondary">' . e($permission->description) . '</span>';
                            })->implode('');;
                            return $html;
                        })->implode('<br>');
                        $html .= "'>" . e($permission->description) . "</a>";
                        return $html;
                    } else {
                        return '<span class="tag btn-sm tag-secondary hidden-text">' . e($permission->description) . '</span>';
                    }
                })->implode(' ');
            })
            ->addColumn('positions', function ($data) use ($positions) {
                $ids = $data->positions->pluck('id');
                return $positions->map(function ($position) use ($ids) {
                    if ($ids->contains($position->id)) {
                        $html = "<a class='tag btn-sm tag-outline-primary' href='#' data-html='true' data-toggle='popover' data-trigger='hover' data-placement='top' data-content='";
                        $html .= $position->childs->map(function ($position) use ($ids) {
                            return $ids->contains($position->id)
                                ? '<span class="tag btn-sm tag-primary">' . e($position->name) . '</span> '
                                : '<span class="tag btn-sm tag-secondary">' . e($position->name) . '</span> ';
                        })->implode('<br>');
                        $html .= "'>" . e($position->name) . "</a>";
                        return $html;
                    } else {
                        return '<span class="tag btn-sm tag-secondary hidden-text">' . e($position->name) . '</span>';
                    }
                })->implode(' ');
            })
            ->editColumn('role', function ($data) {
                return count($data->users) && count($data->permissions) && count($data->positions)
                    ? e($data->role)
                    : "<span class='tag btn-sm tag-danger'>" . e($data->role) . "</span>";
            })
            ->filter(function ($query) use ($request) {
                if (!empty($search = $request->search['value'])) {
                    $terms = explode(' ', $search);
                    $query->where(function ($q) use ($terms) {
                        foreach ($terms as $term) {
                            $q->where(function ($innerQ) use ($term) {
                                $innerQ->where('role', 'like', "%$term%")
                                    ->orWhereHas('users', function ($roleQuery) use ($term) {
                                        $roleQuery->where('username', 'like', "%$term%");
                                    })
                                    ->orWhereHas('permissions', function ($roleQuery) use ($term) {
                                        $roleQuery->where('description', 'like', "%$term%");
                                    })
                                    ->orWhereHas('positions', function ($roleQuery) use ($term) {
                                        $roleQuery->where('name', 'like', "%$term%");
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
