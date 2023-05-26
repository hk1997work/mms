<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_id', 'factory', 'fullname'
    ];

    public function toTool()
    {
        return $this->belongsTo(Tool::class, 'tool_id', 'id');
    }

    public function getNumbers()
    {
        return $this->hasMany(Number::class,'factory_id','id')->orderBy('state_id')->orderBy('number');
    }
}
