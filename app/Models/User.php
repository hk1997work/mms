<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Yajra\DataTables\DataTables;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username', 'password',
    ];

    //当前用户所有角色
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')->orderBy('role_id');
    }

    //用户是否有权限
    public function hasPermission($permission)
    {
        return !!$permission->roles->intersect($this->roles)->count();
    }

    public function getList($request)
    {
        $query = User::select([
            'id',
            'username',
        ])->with([
            'roles:name',
        ]);

        return DataTables::of($query)
            ->addColumn('roles', function ($data) {
                return $data->roles->pluck('name')->map(function ($role) {
                    return '<span class="tag btn-sm tag-secondary">' . e($role) . '</span>';
                })->implode(' ');
            })
            ->editColumn('username', function ($data) {
                return count($data->roles)
                    ? e($data->username)
                    : "<span class='tag btn-sm tag-danger'>" . e($data->username) . "</span>";
            })
            ->filter(function ($query) use ($request) {
                if (!empty($search = $request->search['value'])) {
                    $terms = explode(' ', $search);
                    $query->where(function ($q) use ($terms) {
                        foreach ($terms as $term) {
                            $q->where(function ($innerQ) use ($term) {
                                $innerQ->where('username', 'like', "%$term%")
                                    ->orWhereHas('roles', function ($roleQuery) use ($term) {
                                        $roleQuery->where('name', 'like', "%$term%");
                                    });
                            });
                        }
                    });
                }
            })
            ->rawColumns([1, 2])
            ->make(false);
    }
}
