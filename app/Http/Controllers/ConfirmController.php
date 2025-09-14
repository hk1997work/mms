<?php

namespace App\Http\Controllers;

use App\Models\CertificatesView;
use Yajra\DataTables\DataTables;

class ConfirmController extends Controller
{
    public function index()
    {
        return view("confirm.index");
    }

    public function list()
    {
        $query = CertificatesView::select('id', 'order', 'position1', 'certificate_no', 'instrument', 'model', 'number', 'verification_date', 'validity_date', 'department', 'standard', 'remark')->where('valid', 1)->where('sign', 1)->where('category', '校准证书');
        return DataTables::of($query)->make(false);
    }
}
