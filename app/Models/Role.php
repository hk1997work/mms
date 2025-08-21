<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    //当前角色所有用户
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    //当前角色所有权限
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    //当前角色所有岗位
    public function positions()
    {
        return $this->belongsToMany(Position::class);
    }
}
