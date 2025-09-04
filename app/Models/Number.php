<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'remark', 'pid', 'level', 'state_id',
    ];

    public function children()
    {
        return $this->hasMany(Number::class, 'pid')->select('id', 'pid', 'name')->with('children');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'number_id');
    }
}
