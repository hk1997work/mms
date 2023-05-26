<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'instrument', 'model', 'limit', 'accuracy', 'type_id', 'cycle_id', 'abc_id', 'plan_id'
    ];
    public $timestamps = false;

    public function toType()
    {
        return $this->belongsTo(Position::class, 'type_id', 'id');
    }

    public function toCycle()
    {
        return $this->belongsTo(Parameter::class, 'cycle_id', 'id');
    }

    public function toAbc()
    {
        return $this->belongsTo(Parameter::class, 'abc_id', 'id');
    }

    public function toPlan()
    {
        return $this->belongsTo(Parameter::class, 'plan_id', 'id');
    }
}
