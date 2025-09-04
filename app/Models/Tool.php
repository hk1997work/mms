<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'instrument', 'model', 'limit', 'accuracy', 'requirement', 'type_id', 'cycle_id', 'abc_id', 'plan_id', 'vulnerable'
    ];

    public function factories()
    {
        return $this->hasMany(Number::class,'pid');
    }
}
