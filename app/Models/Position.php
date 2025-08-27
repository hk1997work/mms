<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'pid', 'level', 'sort', 'sign',
    ];

    public function children()
    {
        return $this->hasMany(Position::class, 'pid')->select('id', 'pid', 'name', 'code')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(Position::class, 'pid')->select('id', 'pid', 'name');
    }

    public function certificate()
    {
        return $this->hasMany(Certificate::class, 'position_id');
    }

    public function tool()
    {
        return $this->hasMany(Tool::class, 'type_id');
    }
}
