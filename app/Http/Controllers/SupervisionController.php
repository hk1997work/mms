<?php

namespace App\Http\Controllers;

use App\Models\CertificatesView;
use Illuminate\Http\Request;

class SupervisionController extends Controller
{
    public function show($id)
    {
        $certificates = CertificatesView::where('type', '计量器具')->where('valid', 1)->whereIn('id', explode(',', $id))->orderBy('order')->get();
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
        return view('supervision.show', compact('str'));
    }
}
