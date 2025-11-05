<?php

namespace App\Http\Controllers;

use App\Models\CertificatesView;

class SupervisionController extends Controller
{
    public function show($id, $output = false)
    {
        $certificates = CertificatesView::where('valid', 1)->whereIn('id', explode(',', $id))->orderBy('order')->get();
        $arr = [];
        $str = "计量器具检查：";
        foreach ($certificates as $certificate) {
            $arr[$certificate->position4][$certificate->position1][] = "{$certificate->instrument}，编号：$certificate->number";
        }
        foreach ($arr as $key => $value) {
            $str .= "<br>{$key}：";
            foreach ($value as $key1 => $value1) {
                $str .= "<br>{$key1}岗位：" . implode('；', $value1) . "；";
            }
        }
        $str .= "<br>以上计量器具满足使用要求，标签完好，与计量器具台账一致。";
        return $output ? $str : view('supervision.show', compact('str'));
    }
}
