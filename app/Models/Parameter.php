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

    public function categories()
    {
        return $this->hasMany(Certificate::class, 'category_id');
    }

    public function departments()
    {
        return $this->hasMany(Certificate::class, 'department_id');
    }

    public function cycles()
    {
        return $this->hasMany(Tool::class, 'cycle_id');
    }

    public function abcs()
    {
        return $this->hasMany(Tool::class, 'abc_id');
    }

    public function plans()
    {
        return $this->hasMany(Tool::class, 'plan_id');
    }

    public function states()
    {
        return $this->hasMany(Number::class, 'state_id');
    }
}
