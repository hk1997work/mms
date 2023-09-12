<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Factory;
use App\Models\Number;
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

    function getStandard(Request $request, $path = '')
    {
        if ($request->hasFile('file_certificate')) {
            $path = $request->file('file_certificate')->path();
        }
        exec("python storage/get_standard_from_pdf.py 2>&1 " . $path, $out, $status);
        if ($status == 0) {
            return $out;
        } else {
            return '读取失败';
        }
    }

    function getInfo(ToolsView $tool)
    {
        return $tool;
    }

    function getFactories($tool_id)
    {
        $factories = Factory::where('tool_id', $tool_id)->orderBy('factory')->get();
        return $factories;
    }

    function getNumbers(Factory $factory_id)
    {
        $numbers = Number::where('factory_id', $factory_id->id)->where(function ($query) {
            $query->where('state_id', Parameter::where('name', '待检')->first()->id)
                ->orWhere('id', isset($_GET['number_id']) ? $_GET['number_id'] : 0);
        })->orderBy('number')->get();
        return $numbers;
    }

    function pdf(Request $request)
    {
        if ($request->hasFile('file_certificate')) {
            $file = $request->file('file_certificate');
            $file->storeAs('public/' . \Auth::user()->username . '/orc/', "1.pdf");
            exec("python storage/get_qr_code_from_pdf.py " . \Auth::user()->username . " 2>&1", $out, $status);
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
        exec("python storage/get_jpg_form_pdf.py " . $id . " 2>&1", $out, $status);
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
            'certificate_name' => ['#txtQJMC', 'text'],
            'model' => ['#txtXHGG', 'text'],
            'verification_date' => ['#txtJDRQ', 'text'],
        );
        //采集
        $data[] = QueryList::get($url)->rules($rules)->query()->getData();
        $data[1]['department'] = '省计量院';
        //查看采集结果
        return $data;
    }

    function nanjing_decode($str)
    {
        #base64解密
        $str = base64_decode($str);
        #AES和HEX解密
        $key = "njmind.comnjsjly";
        $str = openssl_decrypt(
            $str,
            'aes-128-ecb', // AES 加密算法和模式
            $key,
            OPENSSL_RAW_DATA
        );
        #base64解密
        $str = base64_decode($str);
        return $str;
    }

    function nanjing_encode($str)
    {
        #base64加密
        $str = base64_encode($str);
        #AES和HEX加密
        $key = "njmind.comnjsjly";
        $str = openssl_encrypt(
            $str,
            'aes-128-ecb', // AES 加密算法和模式
            $key,
            OPENSSL_RAW_DATA
        );
        #base64加密
        $str = base64_encode($str);
        return $str;
    }

    function jiangsu_decode($str,$key)
    {
        #base64解密
        $str = base64_decode($str);
        #AES和HEX解密
        $str = openssl_decrypt(
            $str,
            'aes-128-ecb', // AES 加密算法和模式
            $key,
            OPENSSL_RAW_DATA
        );
        return $str;
    }

    function jiangsu_encode($str,$key)
    {
        #AES和HEX加密
        $str = openssl_encrypt(
            $str,
            'aes-128-ecb', // AES 加密算法和模式
            $key,
            OPENSSL_RAW_DATA
        );
        #base64加密
        $str = base64_encode($str);
        return $str;
    }
}
