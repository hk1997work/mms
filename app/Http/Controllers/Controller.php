<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\Factory;
use App\Models\Number;
use App\Models\NumbersView;
use App\Models\Parameter;
use App\Models\ToolsView;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use QL\QueryList;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function getSn($position_id)
    {
        DB::select('CALL get_sn(?,@sn)', [$position_id]);
        return DB::select('SELECT @sn AS sn')[0]->sn;
    }

    function getStandard($tool_id)
    {
        $standard = CertificatesView::where('tool_id', $tool_id)->orderBy('updated_at', 'desc')->first();
        if ($standard) {
            return $standard->standard_id;
        }
        return null;
    }

    function getInfo(ToolsView $tool_id)
    {
        $arr['tool'] = $tool_id;
        $arr['factories'] = Factory::where('tool_id', $tool_id->id)->orderBy('factory')->get();
        return $arr;
    }

    function getNumbers(Factory $factory_id)
    {
        if (isset($_GET['number_id'])) {
            $numbers = Number::where('factory_id', $factory_id->id)->where(function ($query) {
                $query->where('state_id', Parameter::where('name', '待检')->first()->id)
                    ->orWhere('id', $_GET['number_id']);
            })->orderBy('number')->get();
        } else {
            $numbers = Number::where('factory_id', $factory_id->id)->where('state_id', Parameter::where('name', '待检')->first()->id)->orderBy('number')->get();
        }
        return $numbers;
    }

    function pdf(Request $request)
    {
        if ($request->hasFile('file_certificate')) {
            $file = $request->file('file_certificate');
            $file->storeAs('public/' . \Auth::user()->username . '/orc/', "1.pdf");
            exec("python storage/read_qr_code_from_pdf.py " . \Auth::user()->username . " 2>&1", $out, $status);
            if ($status == 0 && count($out) > 0) {
                switch (substr($out[0], 0, 20)) {
                    case '':
                        return '识别二维码失败';
                    case 'http://lims.njsjly.c':
                        return ['nj', explode('?zsId=', $out[0])[1]];
                    case 'https://serv.jsmi.co':
                        return ['js_new', explode('?zsh=', $out[0])[1]];
                    case 'https://www.jsmi.com.':
                        return $this->jiangsu_url_old($out[0]);
                    default:
                        return '识别成功,未接入API,请联系管理员.';
                }
            } else {
                return '识别失败.';
            }
        }
    }

    function getCertificateNo($certificate_no)
    {
        return !!Certificate::where('certificate_no', $certificate_no)->count();
    }

    function getNumber($number)
    {
        $number = Number::where('number', $number)->get();
        if ($number->count() == 1) {
            $factory = Factory::where('id', $number->first()->factory_id)->first();
            $arr['number_id'] = $number->first()->id;
            $arr['factory_id'] = $factory->id;
            $arr['tool_id'] = $factory->tool_id;
            return $arr;
        }
        return false;
    }

    function pdf2jpg($id)
    {
        $pdf = "storage/certificate/$id.pdf";
        $path = "storage/jpg/$id";

        if (!file_exists($pdf)) {
            return '文件不存在';
        }
        if (File::isDirectory($path)) {
            File::deleteDirectory($path);
        }
        File::makeDirectory($path, 0777, true, true);
        exec("python storage/pdf2jpg.py " . $id . " 2>&1", $out, $status);
    }

    function addFileToZip($path, $zip)
    {
        $handler = opendir($path);
        while (($filename = readdir($handler)) !== false) {
            if ($filename != '.' && $filename != '..') {
                if (is_dir($path . '/' . $filename)) {
                    $this->addFileToZip($path . '/' . $filename, $zip);
                } else {
                    $zip->addFile($path . '/' . $filename, str_replace('storage/' . \Auth::user()->username . '/', '', $path) . '/' . $filename);
                }
            }
        }
        closedir($handler);
    }

    function jiangsu_url_old($url)
    {
        //采集规则
        $data[0] = 'js_old';
        $rules = array(
            'category' => ['#txtZSLX', 'text'],
            'tool' => ['#txtQJMC', 'text'],
            'factory' => ['#txtZZC', 'text'],
            'number' => ['#txtCCBH', 'text'],
            'certificate_no' => ['#txtZSH', 'text'],
            'model' => ['#txtXHGG', 'text'],
            'verification_date' => ['#txtJDRQ', 'text'],
        );
        //采集
        $data[] = QueryList::get($url)->rules($rules)->query()->getData();
        $data[1]['department'] = '省计量院';
        //查看采集结果
        return $data;
    }
}
