<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificatesView extends Model
{
    use HasFactory;

    public function standards()
    {
        return $this->belongsToMany(StandardsView::class, 'certificate_standard', 'certificate_id', 'standard_id')->orderBy('standard_id');
    }
}
