<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'pid', 'level', 'sort', 'sign', 'check',
    ];

    public function children()
    {
        return $this->hasMany(Position::class, 'pid')->select('id', 'pid', 'name', 'code')->orderBy('sort')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(Position::class, 'pid');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'position_id');
    }

    public function tools()
    {
        return $this->hasMany(Tool::class, 'type_id');
    }
}
