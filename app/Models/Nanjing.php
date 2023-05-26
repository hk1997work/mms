<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nanjing extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_no', 'instrument', 'model', 'number', 'verification_date', 'remark', 'pdf'
    ];
}
