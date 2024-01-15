<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificatesView extends Model
{
    use HasFactory;

    public function toNumberCertificates()
    {
        return $this->hasMany(CertificatesView::class, 'number_id', 'number_id')->orderBy('verification_date');
    }

    public function standards()
    {
        return $this->belongsToMany(StandardsView::class, 'standard_certificate', 'certificate_id', 'standard_id')->withPivot(['certificate_id', 'standard_id'])->orderBy('standard_id');
    }
}
