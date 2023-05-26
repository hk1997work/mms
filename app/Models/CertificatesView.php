<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificatesView extends Model
{
    use HasFactory;

    public function toPositionCertificates()
    {
        return $this->hasMany(CertificatesView::class, 'position_id', 'position_id')->where('sn', $this->sn)->orderBy('verification_date','desc');
    }

    public function toNumberCertificates()
    {
        return $this->hasMany(CertificatesView::class, 'number_id', 'number_id')->orderBy('verification_date');
    }
}
