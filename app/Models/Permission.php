<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'pid', 'level', 'sort', 'sort_str',
    ];

    //当前权限所有角色
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id')->withPivot(['permission_id', 'role_id'])->orderBy('id');
    }

    public function children()
    {
        return $this->hasMany(Permission::class, 'pid', 'id')->orderBy('sort')->select('id', 'pid', 'description')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(Permission::class, 'pid', 'id')->select('id', 'pid', 'description')->with('parent');
    }

    public function getList($request)
    {
        $query = self::select('id', 'description', 'pid', 'level', 'name', 'sort')
            ->with('roles:name', 'children', 'parent')
            ->orderBy('sort_str');

        return DataTables::of($query)
            ->editColumn('description', function ($data) {
                return $data->level == 1
                    ? "<span class='badge bg-warning-subtle border border-warning-subtle text-warning-emphasis'>" . e($data->description) . "</span>"
                    : '';
            })
            ->editColumn('pid', function ($data) {
                if ($data->level == 1) {
                    return "<span class='badge btn btn-outline-secondary text-secondary-emphasis btn-add' data-pos='right' data-menu='permission' data-id='" . e($data->id) . "'>增加</span>";
                } elseif ($data->level == 2) {
                    return "<span class='badge bg-success-subtle border border-success-subtle text-success-emphasis'>" . e($data->description) . "</span>";
                }
            })
            ->editColumn('level', function ($data) {
                if ($data->level == 2) {
                    return "<span class='badge btn btn-outline-secondary text-secondary-emphasis btn-add' data-pos='right' data-menu='permission' data-id='" . e($data->id) . "'>增加</span>";
                } elseif ($data->level == 3) {
                    return "<span class='badge bg-info-subtle border border-info-subtle text-info-emphasis'>" . e($data->description) . "</span>";
                }
            })
            ->editColumn('name', function ($data) {
                return count($data->roles)
                    ? e($data->name)
                    : "<span class='badge bg-danger-subtle border border-danger-subtle text-danger-emphasis'>" . e($data->name) . "</span>";
            })
            ->editColumn('sort', function ($data) {
                return $data->roles->pluck('name')->map(function ($role) {
                    return "<span class='badge bg-secondary-subtle border border-secondary-subtle text-secondary-emphasis'>" . e($role) . "</span>";
                })->implode(' ');
            })
            ->filter(function ($query) use ($request) {
                if (!empty($search = $request->search['value'])) {
                    $terms = explode(' ', $search);
                    $query->where(function ($q) use ($terms) {
                        foreach ($terms as $term) {
                            $q->where(function ($innerQ) use ($term) {
                                $innerQ->where('name', 'like', "%$term%")
                                    ->orWhere('description', 'like', "%$term%")
                                    ->orWhereHas('roles', function ($innerQ) use ($term) {
                                        $innerQ->where('name', 'like', "%$term%");
                                    })
                                    ->orWhereHas('children', function ($innerQ) use ($term) {
                                        $innerQ->where('description', 'like', "%$term%");
                                    })
                                    ->orWhereHas('children.children', function ($innerQ) use ($term) {
                                        $innerQ->where('description', 'like', "%$term%");
                                    })
                                    ->orWhereHas('parent', function ($innerQ) use ($term) {
                                        $innerQ->where('description', 'like', "%$term%");
                                    })
                                    ->orWhereHas('parent.parent', function ($innerQ) use ($term) {
                                        $innerQ->where('description', 'like', "%$term%");
                                    });
                            });
                        }
                    });
                }
            })
            ->removeColumn('children', 'parent', 'roles')
            ->rawColumns([1, 2, 3, 4, 5])
            ->make(false);
    }
}
