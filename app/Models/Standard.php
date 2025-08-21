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

    public function children()
    {
        return $this->hasMany(Standard::class,'pid')->select('id', 'pid', 'name')->with('children');
    }

    public function certificate()
    {
        return $this->belongsToMany(Certificate::class);
    }
}
