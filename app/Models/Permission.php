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

    public function children()
    {
        return $this->hasMany(Permission::class, 'pid')->select('id', 'pid', 'description')->with('children');
    }

    //当前权限所有角色
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
