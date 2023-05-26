<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactoriesView extends Model
{
    use HasFactory;

    public function toTool()
    {
        return $this->belongsTo(Tool::class, 'tool_id', 'id');
    }

    public function getNumbers()
    {
        return $this->hasMany(NumbersView::class,'factory_id','id')->orderBy('state_id')->orderBy('number');
    }
}
