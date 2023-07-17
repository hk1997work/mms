<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username', 'password'
    ];

    //当前用户所有角色
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')->withPivot(['user_id', 'role_id'])->orderBy('role_id');
    }

    //增加用户角色
    public function addRole($role)
    {
        return $this->roles()->save($role);
    }

    //删除用户角色
    public function deleteRole($role)
    {
        return $this->roles()->detach($role);
    }

    //用户是否有权限
    public function hasPermission($permission)
    {
        return !!$permission->roles->intersect($this->roles)->count();
    }
}
