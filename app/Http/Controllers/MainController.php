<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\Tool;
use App\Models\ToolsView;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MainController extends Controller
{
    public function index()
    {
        //证书数量
        $certificates = CertificatesView::get();
        $pdf_count = 0;
        foreach ($certificates as $certificate) {
            if (Storage::exists("public/certificate/$certificate->id.pdf")) {
                $pdf_count += 1;
            }
            else{
                dd($certificate->id);
            }
        }
        //检定计划
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $month = now()->addMonths($i)->format('Y-m');
            $months[] = $month;
        }
        $year_plan = CertificatesView::selectRaw("DATE_FORMAT(validity_date,'%Y-%m') MONTH, IFNULL(SUM(IF(plan LIKE '%送检%',1,0)),0) sj, IFNULL(SUM(IF(plan LIKE '%报检%',1,0)),0) bj")
            ->whereIn(DB::raw("DATE_FORMAT(validity_date, '%Y-%m')"), $months)
            ->where('valid', 1)
            ->where('sign', 0)
            ->orderBy('MONTH')
            ->groupBy('MONTH')
            ->get();
        $month_plan = CertificatesView::where('sign', 0)->where('validity_date', 'LIKE', $year_plan->first()->MONTH . "%")->count();
        //送检、报检计划
        $plans = DB::table('plan_views')->get();
        //备用
        $standbys = DB::table('standby_views')->get();
        return view("main.index", compact('pdf_count', 'certificates', 'year_plan', 'month_plan', 'plans', 'standbys'));
    }
}
