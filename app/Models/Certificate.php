<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'sn', 'certificate_name', 'position_id', 'certificate_no', 'number_id', 'verification_date', 'validity_date', 'valid', 'category_id', 'department_id', 'start', 'times', 'money', 'standard_id', 'remark'];

    public function toPosition()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    public function toNumber()
    {
        return $this->belongsTo(Number::class, 'number_id', 'id');
    }

    public function toCategory()
    {
        return $this->belongsTo(Parameter::class, 'category_id', 'id');
    }

    public function toDepartment()
    {
        return $this->belongsTo(Parameter::class, 'department_id', 'id');
    }
}
