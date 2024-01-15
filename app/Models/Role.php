<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

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
}
