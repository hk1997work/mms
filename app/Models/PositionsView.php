<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionsView extends Model
{
    use HasFactory;

    //获取父级
    public function toParent()
    {
        return $this->belongsTo(Position::class,'pid','id');
    }
    //获取子级
    public function toChildrens()
    {
        return $this->hasMany(PositionsView::class,'pid','id')->orderBy('sort');
    }
}
