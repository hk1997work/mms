<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'pid', 'level'
    ];

    //获取父级
    public function toParent()
    {
        return $this->belongsTo(Standard::class, 'pid', 'id');
    }

    //获取子级
    public function toChildrens()
    {
        return $this->hasMany(Standard::class, 'pid', 'id')->orderBy('name','desc');
    }
}
