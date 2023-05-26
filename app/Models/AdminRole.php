<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    use HasFactory;

    protected $table = "admin_roles";
    protected $fillable = ['name'];

    //当前角色所有权限
    public function permissions()
    {
        return $this->belongsToMany(AdminPermission::class, 'admin_permission_role', 'role_id', 'permission_id')->withPivot(['permission_id', 'role_id'])->orderBy('sort');
    }

    //增加角色权限
    public function addPermission($permission)
    {
        return $this->permissions()->save($permission);
    }

    //删除角色权限
    public function deletePermission($permission)
    {
        return $this->permissions()->detach($permission);
    }

    //当前角色所有岗位
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'admin_role_position', 'role_id', 'position_id')->withPivot(['position_id', 'role_id'])->orderBy('sort');
    }

    //增加角色岗位
    public function addPosition($position)
    {
        return $this->positions()->save($position);
    }

    //删除角色岗位
    public function deletePosition($position)
    {
        return $this->positions()->detach($position);
    }

    //当前角色所有用户
    public function users()
    {
        return $this->belongsToMany(User::class, 'admin_role_user', 'role_id', 'user_id')->withPivot(['user_id', 'role_id']);
    }

}
