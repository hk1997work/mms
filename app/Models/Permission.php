<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'pid', 'level', 'sort', 'icon'
    ];

    //当前权限所有角色
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id')->withPivot(['permission_id', 'role_id'])->orderBy('id');
    }
    //获取父级
    public function toParent()
    {
        return $this->belongsTo(Permission::class,'pid','id');
    }
    //获取子级
    public function toChildrens()
    {
        return $this->hasMany(Permission::class,'pid','id')->orderBy('sort');
    }
}
