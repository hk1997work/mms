<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumbersView extends Model
{
    use HasFactory;

    public function toFactory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }

    public function toState()
    {
        return $this->belongsTo(Parameter::class, 'state_id', 'id');
    }

    public function toCertificate()
    {
        return $this->belongsTo(Certificate::class, 'id', 'number_id')->whereRaw('certificates.validity_date = (SELECT MAX(validity_date) AS validity_date FROM certificates c where number_id = certificates.number_id)');
    }
}
