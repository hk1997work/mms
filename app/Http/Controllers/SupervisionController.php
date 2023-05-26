<?php

namespace App\Http\Controllers;

use App\Models\CertificatesView;
use Illuminate\Http\Request;

class SupervisionController extends Controller
{
    public function index()
    {
        $certificates = CertificatesView::where('type', '计量器具')->where('valid', 1)->where('sign', 0)->get();
        return view("supervision.index", compact('certificates'));
    }

    public function store(Request $request)
    {
        if (isset($request->cb)) {
            $certificates = CertificatesView::where('type', '计量器具')->where('valid', 1)->whereIn('id', array_keys($request->cb))->get();
            $unit = '';
            $position = '';
            $str = "计量器具检查：";
            foreach ($certificates as $certificate) {
                if ($certificate->unit3 != $unit) {
                    $str = $str . "<br>" . $certificate->unit3 . "：";
                }
                if ($certificate->position != $position) {
                    $str = $str . "<br>" . $certificate->position . "岗位：";
                }
                $str = $str . $certificate->instrument . "，编号：" . $certificate->number . "；";
                $unit = $certificate->unit3;
                $position = $certificate->position;
            }
            $str = $str . "<br>以上计量器具满足使用要求，标签完好，与计量器具台账一致。";
            return $str;
        }
        return false;
    }
}
