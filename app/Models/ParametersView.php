<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametersView extends Model
{
    use HasFactory;

    //获取父级
    public function toParent()
    {
        return $this->belongsTo(Parameter::class,'pid','id');
    }
    //获取子级
    public function toChildrens()
    {
        return $this->hasMany(ParametersView::class,'pid','id')->orderBy('sort');
    }
}
