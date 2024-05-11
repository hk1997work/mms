<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\Position;
use App\Models\Tool;
use App\Models\ToolsView;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MainController extends Controller
{
    public function index()
    {
        $start_year = now()->subYear()->month(12)->day(26);
        $end_year = now()->month(12)->day(26);
        $start_month = now()->startOfMonth()->subMonth()->day(26);
        $end_month = now()->startOfMonth()->day(26);
        $check_id = [];
        $checks = [];
        $check_count = [
            'year' => 0,
            'month' => [],
            'month_total' => 0,
            'day' => 0,
        ];
        $positions = Position::select('name')->whereIn('name', ['南京钢管分公司','防腐分公司','科技质量中心'])->groupBy('name')->orderByRaw('MIN(sort)')->get();
        foreach ($positions as $position) {
            $check_count['month'][$position->name] = 0;
        }
        //证书数量
        $certificates = CertificatesView::orderBy('order')->get();
        $pdf_count = 0;
        foreach ($certificates as $certificate) {
            if (Storage::exists("public/certificate/$certificate->id.pdf")) {
                $pdf_count += 1;
            }
            if ($certificate->verification_date <= $end_year && $certificate->validity_date >= $start_year) {
                $folderPath = "public/check/$certificate->id";
                if (Storage::exists($folderPath)) {
                    $files = Storage::files($folderPath);
                    foreach ($files as $file) {
                        $dateString = substr($file, 18, 10);
                        $date = Carbon::createFromFormat('Y-m-d', $dateString);
                        if ($date > $start_year && $date < $end_year) {
                            $check_count['year'] += 1;
                            if ($date > $start_month && $date < $end_month) {
                                $check_count['month'][$certificate->unit2] += 1;
                                $check_count['month_total'] += 1;
                                if ($dateString == date('Y-m-d')) {
                                    $check_count['day'] += 1;
                                    $check_id[] = $certificate->id;
                                }
                            }
                        }
                    }
                }
            }
        }
        $copy = (new SupervisionController)->show(implode(',', $check_id), true);
        //检定计划
        $year_plan = DB::table('year_plan_views')->get();
        $month_plan = CertificatesView::where('sign', 0)->where('validity_date', 'LIKE', $year_plan->first()->MONTH . "%")->count();
        //送检、报检计划
        $plans = DB::table('plan_views')->get();
        //备用
        $standbys = DB::table('standby_views')->get();
        return view("main.index", compact('start_year', 'end_year', 'start_month', 'end_month', 'checks', 'copy', 'check_count', 'pdf_count', 'certificates', 'year_plan', 'month_plan', 'plans', 'standbys', 'positions'));
    }
}
