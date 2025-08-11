<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'pid', 'level', 'sort', 'sign'
    ];

    public function childs()
    {
        return $this->hasMany(Position::class, 'pid', 'id')->orderBy('sort')->with('childs');
    }
}
