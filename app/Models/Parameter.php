<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'pid', 'level', 'sort',
    ];

    public function children()
    {
        return $this->hasMany(Parameter::class,'pid')->select('id', 'pid', 'name')->with('children');
    }

    public function category()
    {
        return $this->hasMany(Certificate::class, 'category_id');
    }

    public function department()
    {
        return $this->hasMany(Certificate::class, 'department_id');
    }

    public function cycle()
    {
        return $this->hasMany(Tool::class, 'cycle_id');
    }

    public function abc()
    {
        return $this->hasMany(Tool::class, 'abc_id');
    }

    public function plan()
    {
        return $this->hasMany(Tool::class, 'plan_id');
    }

    public function state()
    {
        return $this->hasMany(Number::class, 'state_id');
    }
}
