<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'pid', 'level', 'sort',
    ];

    //当前权限所有角色
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id')->withPivot(['permission_id', 'role_id'])->orderBy('id');
    }

    public function childs()
    {
        return $this->hasMany(Permission::class, 'pid', 'id')->orderBy('sort')->with('childs');
    }
}
