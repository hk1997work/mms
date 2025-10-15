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
        $positions = Position::where('level', 1)->whereIn('name', ['南京钢管分公司', '防腐分公司', '科技质量中心'])->orderBy('sort')->pluck('name');
        foreach ($positions as $position) {
            $check_count['month'][$position]['count'] = 0;
            $check_count[$position]['success'] = 0;
            $check_count[$position]['info'] = 0;
            $check_count[$position]['warning'] = 0;
            $check_count[$position]['danger'] = 0;
            $check_count[$position]['total'] = 0;
        }
        //证书数量
        $certificates = CertificatesView::orderBy('order')->get();
        $pdf_count = 0;
        $check_pdf = '';
        foreach ($certificates as $certificate) {
            if (Storage::exists("public/certificate/$certificate->id.pdf")) {
                $pdf_count += 1;
            } else {
                $check_pdf .= $certificate->certificate_no . ';';
            }
            if ($certificate->verification_date <= $end_year && $certificate->validity_date >= $start_year) {
                $folderPath = "public/check/$certificate->id";
                if (Storage::exists($folderPath)) {
                    $files = Storage::files($folderPath);
                    foreach ($files as $file) {
                        $dateString = substr(basename($file), 0, 10);
                        $date = Carbon::createFromFormat('Y-m-d', $dateString);
                        if ($date > $start_year && $date < $end_year) {
                            $check_count['year'] += 1;
                            if ($date > $start_month && $date < $end_month) {
                                if (isset($check_count[$certificate->position4])) {
                                    $check_count['month'][$certificate->position4]['count'] += 1;
                                }
                                $check_count['month_total'] += 1;
                            }
                        }
                        if ($dateString == date('Y-m-d')) {
                            $check_count['day'] += 1;
                            $check_id[] = $certificate->id;
                        }
                    }
                }
            }
            if ($certificate->valid == 1 && $certificate->sign == 1 && ($certificate->position3 == '计量器具' || $certificate->position3 == '检测仪表')) {
                $folderPath = "storage/check/$certificate->id";
                if (isset($check_count[$certificate->position4])) {
                    if (file_exists($folderPath) && is_dir($folderPath)) {
                        $files = scandir($folderPath);
                        $certificate->count = count($files) - 2;
                        if ($certificate->count > 0) {
                            natsort($files);
                            $fileDate = Carbon::createFromFormat('Y-m-d', substr(end($files), 0, 10));
                            $fileAge = $fileDate->diffInDays(Carbon::now());
                            if ($fileAge < 90) {
                                $check_count[$certificate->position4]['success'] += 1;
                            } elseif ($fileAge < 180) {
                                $check_count[$certificate->position4]['info'] += 1;
                            } else {
                                $check_count[$certificate->position4]['warning'] += 1;
                            }
                        } else {
                            $check_count[$certificate->position4]['danger'] += 1;
                        }
                    } else {
                        $check_count[$certificate->position4]['danger'] += 1;
                    }
                    $check_count[$certificate->position4]['total'] += 1;
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
        return view("main.index", compact('start_year', 'end_year', 'start_month', 'end_month', 'checks', 'copy', 'check_count', 'pdf_count', 'certificates', 'year_plan', 'month_plan', 'plans', 'standbys', 'positions', 'check_pdf'));
    }
}
