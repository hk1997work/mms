<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateRequest;
use App\Http\Requests\NanjingRequest;
use App\Models\Certificate;
use App\Models\Factory;
use App\Models\Nanjing;
use App\Models\Number;
use App\Models\Parameter;
use App\Models\Position;
use App\Models\Standard;
use App\Models\Tool;
use GuzzleHttp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class NanjingController extends Controller
{
    //解析数据
    public function index()
    {
        exec("python storage/nanjing.py 2>&1", $out, $status);
        if ($status == 0) {
            $client = new GuzzleHttp\Client();
            $res = $client->request('GET', 'http://58.213.156.66/cmiims/a/sys/adminECertQuery/listenceInfo?Bp15yk3ja5mdrgvb99vm0wPu+D8CX+pW9Hsvf3FAnmudfLnJsjdh+T8ZvXOygoXi4gSJYHVMdRojzdteV4Uks5hwQk/+LUGqCJ8d66bdXbvRNB02z4f8uJXZhJTzajDLdgWznY9oqtVRG2pV76SL0w==', [
                'headers' => [
                    'cookie' => $out,
                ]
            ]);
            $result = (string)$res->getBody();
            if (mb_substr($result, 0, 1) != "\n") {
                return view('nanjing.index', compact('result', 'out'));
            }
        } else {
            return '读取失败';
        }
    }

    //获取列表
    public function update(Request $request)
    {
        $nanjings = Nanjing::orderBy('verification_date')->get();
        $arr = json_decode($request->json)->data->list;
        $cer = array_column($arr, 'zsbh');
        $no = array_column(Certificate::select('certificate_no')->where('department_id', Parameter::where('name', '市计量院')->first()->id)->get()->toArray(), 'certificate_no');
        $result = array_diff(array_diff($cer, array_intersect($cer, $no)), array_column($nanjings->toArray(), 'certificate_no'));
        $i = 0;
        foreach ($arr as $a) {
            if (in_array($a->zsbh, $result)) {
                $a->order = $i;
                $i++;
                $certificates[] = $a;
            }
        }
        if (isset($certificates)) {
            $json = str_replace('<span style=\"font-family: njmindFont;\">', "<span style=\'font-family: njmindFont;\'>", json_encode($certificates));
        } else {
            $json = null;
            $certificates = null;
        }
        return view('nanjing.show', compact('certificates', 'nanjings', 'json'));
    }

    //删除屏蔽
    public function destroy(Nanjing $nanjing)
    {
        return !!$nanjing->delete();
    }

    //批量录入
    public function create(NanjingRequest $request)
    {
        $arr = json_decode($request->json);
        $tools = Tool::orderBy('instrument')->get();
        $data = $request->data;
        $idList = $request->idList;
        $standards = Standard::where('level', 1)->orderBy('name')->get();
        return view('nanjing.create', compact('arr', 'tools', 'data', 'idList', 'standards'));
    }

    public function store(NanjingRequest $request)
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request('POST', 'http://58.213.156.66/cmiims/a/sys/adminECertQuery/downECert?', [
            'form_params' => [
                'idList' => $request->idList,
                'data' => $request->data,
            ],
            'headers' => [
                'cookie' => Cookie::get('nanjing'),
            ],
        ]);
        $result = (string)$res->getBody();
        if (File::isDirectory("storage/nanjing")) {
            File::deleteDirectory("storage/nanjing");
        }
        File::makeDirectory("storage/nanjing", 0777, true, true);

        file_put_contents("storage/nanjing/" . \Auth::user()->username . ".zip", $result);

        $zip = new ZipArchive;//新建一个ZipArchive的对象
        $pic_dir = 'storage/nanjing/'; // 文件所在的绝对路径
        if ($zip->open('storage/nanjing/' . \Auth::user()->username . ".zip") === TRUE) {
            // 解压缩到某个位置
            $zip->extractTo($pic_dir);
            // 关闭
            $zip->close();
        }
        foreach ($request->json as $json) {
            $arr = json_decode($json);
            $tool = Tool::find($request->tool_id[$arr->order]);
            $cycle = Parameter::find($tool->cycle_id)->name;
            $add_date = mb_substr($cycle, 0, strlen($cycle) - 3);
            $certificate_controller = new CertificateController;
            $certificate = new CertificateRequest();
            $certificate->position_id = DB::table('positions AS p1')->select('p1.*')->leftJoin('positions as p2', 'p2.id', 'p1.pid')->leftJoin('positions as p3', 'p3.id', 'p2.pid')
                ->where('p1.name', '备用')->where('p1.level', 5)->where('p3.pid', $tool->type_id)->first()->id;
            $certificate->sn = $this->getSn(Position::find($certificate->position_id));
            $certificate->category_id = Parameter::where('name', "$arr->zsType")->first()->id;
            $certificate->tool_id = $request->tool_id[$arr->order];
            $certificate->factory_id = $request->factory_id[$arr->order];
            $certificate->number_id = $request->number_id[$arr->order];
            $certificate->certificate_no = $arr->zsbh;
            $certificate->department_id = Parameter::where('name', '市计量院')->first()->id;
            $certificate->verification_date = $request->verification_date[$arr->order];
            if (mb_substr($cycle, -1, 1) == '天') {
                $certificate->validity_date = date('Y-m-d', strtotime("-1 day", strtotime("+$add_date day", strtotime($certificate->verification_date))));
            }
            if (mb_substr($cycle, -1, 1) == '月') {
                $certificate->validity_date = date('Y-m-d', strtotime("-1 day", strtotime("+$add_date month", strtotime($certificate->verification_date))));
            }
            $certificate->start = date('Y');
            $certificate->times = 1;
            $certificate->money = 0;
            $certificate->standard_id = $request->standard_id[$arr->order];
            $certificate->remark = $request->remark[$arr->order];

            $c = Certificate::where('number_id', $request->number_id[$arr->order])->where('valid', 1)->get();
            if ($c->count() == 1) {
                $c[0]->valid = 0;
                $c[0]->save();
                $certificate->sn = $c[0]->sn;
                $certificate->position_id = $c[0]->position_id;
                $certificate->start = $c[0]->start;
                $certificate->times = $c[0]->times + 1;
                $certificate->remark = $c[0]->remark;
            }
            if ($cer_id = $certificate_controller->store($certificate)) {
                if (isset($arr->sbbh) && $arr->sbbh != '/') {
                    $arr->ccbh = $arr->sbbh;
                }
                if (Storage::exists("/public/nanjing/$arr->zsbh" . "_$arr->name" . "_$arr->ccbh" . "_" . str_replace('-', '', $arr->jdrq . ".pdf"))) {
                    Storage::move("/public/nanjing/$arr->zsbh" . "_$arr->name" . "_$arr->ccbh" . "_" . str_replace('-', '', $arr->jdrq . ".pdf"), "/public/certificate/$cer_id.pdf");
                }
            } else {
                return false;
            }
        }
        return true;
    }

    public function filter(Request $request)
    {
        $arr['certificate_no'] = $request->certificate_no;
        $arr['instrument'] = $request->instrument;
        $arr['model'] = $request->model;
        $arr['number'] = $request->number;
        $arr['verification_date'] = $request->verification_date;
        $arr['remark'] = isset($request->remark) ? $request->remark : '';
        $arr['pdf'] = $request->pdf;
        return !!Nanjing::create($arr);
    }

    public function download(Request $request)
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request('POST', 'http://58.213.156.66/cmiims/a/sys/adminECertQuery/downECert?', [
            'form_params' => [
                'idList' => $request->idList,
                'data' => $request->data,
            ],
            'headers' => [
                'cookie' => Cookie::get('nanjing'),
            ],
        ]);
        $result = (string)$res->getBody();
        if (File::isDirectory("storage/down") == false) {
            File::makeDirectory("storage/down", 0777, true, true);
        }
        file_put_contents("storage/down/" . \Auth::user()->username . "证书.zip", $result);
        return Storage::download("public/down/" . \Auth::user()->username . "证书.zip", date('Y-m-d') . "证书.zip");
    }

    public function number(Factory $factory_id)
    {
        $numbers = Number::where('factory_id', $factory_id->id)->where(function ($query) {
            $query->where('state_id', Parameter::where('name', '待检')->first()->id)
                ->orWhere('state_id', Parameter::where('name', '在用')->first()->id)
                ->orWhere('state_id', Parameter::where('name', '备用')->first()->id);
        })->orderBy('number')->get();
        return $numbers;
    }
}
