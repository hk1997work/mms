<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'sn', 'certificate_name', 'position_id', 'certificate_no', 'number_id', 'verification_date', 'validity_date', 'valid', 'category_id', 'department_id', 'start', 'times', 'money', 'standard_id', 'remark'
    ];

}
