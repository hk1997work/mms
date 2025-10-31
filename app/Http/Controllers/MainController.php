<?php

namespace App\Http\Controllers;

use App\Models\CertificatesView;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MainController extends Controller
{
    public function index()
    {
        # 时间范围
        $date['start_year'] = now()->subYear()->month(12)->day(26);
        $date['end_year'] = now()->month(12)->day(26);
        $date['start_month'] = now()->startOfMonth()->subMonth()->day(26);
        $date['end_month'] = now()->startOfMonth()->day(26);

        //证书数量
        $certificates = CertificatesView::orderBy('order')->get();
        $pdf_count = 0;
        $pdf_miss = '';
        foreach ($certificates as $certificate) {
            if (Storage::exists("public/certificate/$certificate->id.pdf")) {
                $pdf_count += 1;
            } else {
                $pdf_miss .= "$certificate->certificate_no;";
            }
        }




        $copy = (new SupervisionController)->show(implode(',', $check_id), true);
        //检定计划
        $year_plan = DB::table('year_plan_views')->get();
        $month_plan = CertificatesView::where('sign', 0)->where('validity_date', 'LIKE', $year_plan->first()->month . "%")->count();
        //送检、报检计划
        $plans = DB::table('plan_views')->get();
        //备用
        $standbys = DB::table('standby_views')->get();
        return view("main.index", compact('start_year', 'end_year', 'start_month', 'end_month', 'checks', 'copy', 'check_count', 'pdf_count', 'certificates', 'year_plan', 'month_plan', 'plans', 'standbys', 'positions', 'check_pdf'));
    }
}
