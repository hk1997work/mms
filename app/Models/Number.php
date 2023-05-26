<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    use HasFactory;

    protected $fillable = [
        'factory_id', 'number', 'state_id'
    ];

    public function toFactory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }

    public function toState()
    {
        return $this->belongsTo(Parameter::class, 'state_id', 'id');
    }
}
