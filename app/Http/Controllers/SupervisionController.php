<?php

namespace App\Http\Controllers;

use App\Models\CertificatesView;
use Illuminate\Http\Request;

class SupervisionController extends Controller
{
    public function index()
    {
        $certificates = CertificatesView::where('type', '计量器具')->where('valid', 1)->where('sign', 0)->orderBy('order')->get();
        return view("supervision.index", compact('certificates'));
    }

    public function store(Request $request)
    {
        if (isset($request->cb)) {
            $certificates = CertificatesView::where('type', '计量器具')->where('valid', 1)->whereIn('id', array_keys($request->cb))->orderBy('order')->get();
            if (isset($request->input_export)) {
                $str = '';
                foreach ($certificates as $certificate) {
                    $replace = $request->input_export;
                    foreach (collect($certificate)->keys() as $value) {
                        if (strpos($replace, '{' . $value . '}')) {
                            $replace = str_replace('{' . $value . '}', $certificate[$value], $replace);
                        }
                    }
                    $str = $str . $replace;
                }
            } else {
                $unit = '';
                $position = '';
                $str = "计量器具检查：";
                foreach ($certificates as $certificate) {
                    if ($certificate->unit2 != $unit) {
                        $str = $str . "<br>" . $certificate->unit2 . "：";
                    }
                    if ($certificate->position != $position) {
                        $str = $str . "<br>" . $certificate->position . "岗位：";
                    }
                    $str = $str . $certificate->instrument . "，编号：" . $certificate->number . "；";
                    $unit = $certificate->unit2;
                    $position = $certificate->position;
                }
                $str = $str . "<br>以上计量器具满足使用要求，标签完好，与计量器具台账一致。";
            }
            return $str;
        }
        return false;
    }
}
