<?php

namespace App\Http\Controllers;

use App\Models\CertificatesView;

class SupervisionController extends Controller
{
    public function show($id, $output = false)
    {
        $certificates = CertificatesView::where('valid', 1)->whereIn('id', explode(',', $id))->orderBy('order')->get();
        $unit = '';
        $position = '';
        $str = "计量器具检查：";
        foreach ($certificates as $certificate) {
            if ($certificate->position4 != $unit) {
                $str = $str . "<br>" . $certificate->position4 . "：";
            }
            if ($certificate->position1 != $position) {
                $str = $str . "<br>" . $certificate->position1 . "岗位：";
            }
            $str = $str . $certificate->instrument . "，编号：" . $certificate->number . "；";
            $unit = $certificate->position4;
            $position = $certificate->position1;
        }
        $str = $str . "<br>以上计量器具满足使用要求，标签完好，与计量器具台账一致。";
        return $output ? $str : view('supervision.show', compact('str'));
    }
}
