<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificatesView;
use Illuminate\Support\Facades\Storage;

class MainController extends Controller
{
    public function index()
    {
        return view("main.index");
    }

    public function ajax()
    {
        //证书、校准数量
        $certificates = CertificatesView::where('valid', 1)->where('sign', 0)->get();
        $pdf_count = 0;
        $img_count = 0;
        foreach ($certificates as $certificate) {
            if (Storage::exists("public/certificate/$certificate->id.pdf")) {
                $pdf_count += 1;
            }
            if (Storage::exists("public/verification/$certificate->id.pdf")) {
                $img_count += 1;
            }
        }
        $arr['pdf_per'] = intval($pdf_count * 100 / $certificates->count());
        $arr['img_per'] = intval($img_count * 100 / $certificates->count());
        //检定计划
        $arr['plan'] = Certificate::selectRaw("DATE_FORMAT( certificates.validity_date, '%Y.%m' ) MONTH,
	        IFNULL( SUM( IF ( t.plan_id = 135||t.plan_id = 137||t.plan_id = 139, 1, 0 )), 0 ) SJ,
	        IFNULL( SUM( IF ( t.plan_id = 136||t.plan_id = 138||t.plan_id = 140, 1, 0 )), 0 ) BJ ")
            ->leftJoin('numbers AS n', 'n.id', 'certificates.number_id')
            ->leftJoin('factories AS f', 'f.id', 'n.factory_id')
            ->leftJoin('tools AS t', 't.id', 'f.tool_id')
            ->where('certificates.valid', 1)->whereRaw("date_format( validity_date, '%Y%m' ) >= date_format( CURDATE(), '%Y%m' )")
            ->whereDate("certificates.validity_date", '<', date('Y.m', strtotime("+11 month")))
            ->orderBy('MONTH')->groupBy('MONTH')->get()->toArray();

        $complete = Certificate::selectRaw("valid,COUNT(valid) AS count")->whereRaw("date_format( validity_date, '%Y%m' ) = date_format( CURDATE(), '%Y%m' )")->groupBy('valid')->get();
        $arr['complete_per'] = intval($complete[0]->count * 100 / ($complete[0]->count + $complete[1]->count));
        return $arr;
    }
}
