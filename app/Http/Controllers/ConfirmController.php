<?php

namespace App\Http\Controllers;

use App\Models\CertificatesView;

class ConfirmController extends Controller
{
    public function index()
    {
        return view("confirm.index");
    }

    public function list()
    {
        $data = CertificatesView::select('id', 'order', 'position', 'certificate_no', 'instrument', 'model', 'number', 'verification_date', 'validity_date', 'department', 'remark')->where('valid', 1)->where('category', '校准证书')->where('position', '!=', '备用')->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' id='$value[id]' data-url='/storage/certificate/$value[id].pdf'>
                        <label for='$value[id]'></label>
                    </div>";
        }
        return response()->json(['data' => array_map('array_values', $data)]);
    }
}
