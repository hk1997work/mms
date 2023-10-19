<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateRequest;
use App\Http\Requests\JiangsuRequest;
use App\Models\Certificate;
use App\Models\Factory;
use App\Models\Jiangsu;
use App\Models\Number;
use App\Models\Parameter;
use App\Models\PositionsView;
use App\Models\StandardsView;
use App\Models\Tool;
use GuzzleHttp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class JiangsuController extends Controller
{
    public function index()
    {
        $client = new GuzzleHttp\Client(['verify' => false]);
        $res = $client->request('POST', 'https://serv.jsmi.com.cn/auth/oauth/token?randomStr=blockPuzzle&code=vz+/DYdEXOirYS+bLJNqBziFmXN4/KBwjK5vbMa9Rv3pkOfmNP+8oI4sggZaU/gHRC7iN3UCe0OpfdLMsniAdl/cveHjU7NZhukYtwiYllg=&grant_type=password', [
            'form_params' => [
                'username' => '南京巨龙钢管有限公司',
                'password' => 'RBWv3JtZKCI=',
            ],
            'headers' => [
                'Authorization' => 'Basic cGlnOnBpZw==',
                'TENANT-ID' => '2',
            ],
        ]);
        $access_token = json_decode((string)$res->getBody())->access_token;
        $res = $client->request('GET', 'https://serv.jsmi.com.cn/forms/zs/list?current=1&size=9999&zsZsh=&zsDdh=&zsWyxh=&zsQjmc=&zsZsdwmc=南京巨龙钢管有限公司&zsXhgg=&zsCcbh=&zsClfw=&zsZqd=&zsSbbh=&zsDyrqStart=2019-09-01', [
            'cookie' => [
                'Authorization' => $access_token,
                'JSESSIONID' => explode(';', explode('=', $res->getHeaders()['Set-Cookie'][0])[1])[0],
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'TENANT-ID' => '2',
            ],
        ]);
        $result = json_decode((string)$res->getBody())->data->records;
        $jiangsu = Jiangsu::orderBy('verification_date')->get();
        $certificates = [];
        $sql = array_column(Certificate::select('certificate_no')->where('department_id', Parameter::where('name', '省计量院')->first()->id)->get()->toArray(), 'certificate_no');
        $certificate_no = array_diff(array_column($result, 'zsZsh'), $sql, array_column($jiangsu->toArray(), 'certificate_no'));
        foreach ($result as $r) {
            if (in_array($r->zsZsh, $certificate_no)) {
                $certificates[] = $r;
            }
        }
        return view('jiangsu.index', compact('certificates', 'jiangsu', 'access_token'));
    }

    public function create(Request $request)
    {
        $arr = [];
        $check = explode(',', $request->id);
        $tools = Tool::orderBy('instrument')->get();
        $standards = StandardsView::where('level', 2)->orderBy('name1')->get();
        $client = new GuzzleHttp\Client(['verify' => false]);
        $res = $client->request('POST', 'https://serv.jsmi.com.cn/auth/oauth/token?randomStr=blockPuzzle&code=vz+/DYdEXOirYS+bLJNqBziFmXN4/KBwjK5vbMa9Rv3pkOfmNP+8oI4sggZaU/gHRC7iN3UCe0OpfdLMsniAdl/cveHjU7NZhukYtwiYllg=&grant_type=password', [
            'form_params' => [
                'username' => '南京巨龙钢管有限公司',
                'password' => 'RBWv3JtZKCI=',
            ],
            'headers' => [
                'Authorization' => 'Basic cGlnOnBpZw==',
                'TENANT-ID' => '2',
            ],
        ]);
        $access_token = json_decode((string)$res->getBody())->access_token;
        $res = $client->request('GET', 'https://serv.jsmi.com.cn/forms/zs/list?current=1&size=9999&zsZsh=&zsDdh=&zsWyxh=&zsQjmc=&zsZsdwmc=南京巨龙钢管有限公司&zsXhgg=&zsCcbh=&zsClfw=&zsZqd=&zsSbbh=&zsDyrqStart=2019-09-01', [
            'cookie' => [
                'Authorization' => $access_token,
                'JSESSIONID' => explode(';', explode('=', $res->getHeaders()['Set-Cookie'][0])[1])[0],
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'TENANT-ID' => '2',
            ],
        ]);
        $result = json_decode((string)$res->getBody())->data->records;
        foreach ($result as $key => $value) {
            if (in_array($value->zsId, $check)) {
                $res = $client->request('GET', 'https://app.jsmi.com.cn/forms/app-zs/getPath?zsh=' . $value->zsZsh, [
                    'headers' => [
                        'Authorization' => "Bearer $access_token",
                        'TENANT-ID' => '2',
                    ],
                ]);
                $json = json_decode((string)$res->getBody())->data;
                $arr[$key]['path'] = 'https://app.jsmi.com.cn/file/sys-file/downLoadFile//' . $value->zsZsh . '.pdf?bucket=' . $json->bucket . '@fileName=' . $json->zsdz;
                $value->zsCcbh = isset($value->zsCcbh) ? $value->zsCcbh == '/' ? '' : $value->zsCcbh : '';
                $value->zsSbbh = isset($value->zsSbbh) ? $value->zsSbbh == '/' ? '' : $value->zsSbbh : '';
                $number = Number::where('number', $value->zsCcbh . $value->zsSbbh)->get();
                $arr[$key]['json'] = $value;
                $standard = $this->getStandard($request, $arr[$key]['path']);
                $arr[$key]['standard'] = explode(',', $standard[0]);
                $arr[$key]['category'] = hex2bin(str_replace('\x', '', substr($standard[1], 2, -1)));
                if ($number->count() == 1) {
                    $arr[$key]['info'] = 1;
                    $factory = Factory::where('id', $number->first()->factory_id)->first();
                    $arr[$key]['number_id'] = $number->first()->id;
                    $arr[$key]['factory_id'] = $factory->id;
                    $arr[$key]['tool_id'] = $factory->tool_id;
                    $arr[$key]['numbers'] = Number::where('factory_id', $arr[$key]['factory_id'])->get();
                    $arr[$key]['factories'] = Factory::where('tool_id', $arr[$key]['tool_id'])->get();
                } else {
                    $arr[$key]['info'] = 0;
                }
            }
        }
        return view('jiangsu.create', compact('tools', 'standards', 'arr'));
    }

    public function store(JiangsuRequest $request)
    {
        foreach ($request->certificate_no as $key => $value) {
            $tool = Tool::find($request->tool_id[$key]);
            $cycle = Parameter::find($tool->cycle_id)->name;
            $add_date = mb_substr($cycle, 0, strlen($cycle) - 3);
            $certificate_controller = new CertificateController;
            $certificate = new CertificateRequest();
            $certificate->position_id = PositionsView::where('id4', $tool->type_id)->where('name1', '备用')->first()->id;
            $certificate->sn = $this->getSn($certificate->position_id);
            $certificate->category_id = Parameter::where('name', $request->category[$key])->first()->id;
            $certificate->tool_id = $request->tool_id[$key];
            $certificate->factory_id = $request->factory_id[$key];
            $certificate->number_id = $request->number_id[$key];
            $certificate->certificate_no = $request->certificate_no[$key];
            $certificate->certificate_name = $request->certificate_name[$key];
            $certificate->department_id = Parameter::where('name', '省计量院')->first()->id;
            $certificate->verification_date = $request->verification_date[$key];
            if (mb_substr($cycle, -1, 1) == '天') {
                $certificate->validity_date = date('Y-m-d', strtotime("-1 day", strtotime("+$add_date day", strtotime($certificate->verification_date))));
            }
            if (mb_substr($cycle, -1, 1) == '月') {
                $certificate->validity_date = date('Y-m-d', strtotime("-1 day", strtotime("+$add_date month", strtotime($certificate->verification_date))));
            }
            $certificate->start = substr($certificate->verification_date, 0, 4);
            $certificate->times = 1;
            $certificate->money = 0;
            $certificate->standard_id = $request->standard_id[$key];
            $certificate->remark = $request->remark[$key];
            $c = Certificate::where('number_id', $request->number_id[$key])->where('valid', 1)->get();
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
                Storage::put("/public/certificate/" . $cer_id . ".pdf", file_get_contents(str_replace('@', '&', $request->path[$key])));
            } else {
                return false;
            }
        }
        return true;
    }

    public function show($type)
    {
        $info = json_decode(str_replace('@', '#', $_GET['str']));
        $client = new GuzzleHttp\Client(['verify' => false]);
        $res = $client->request('GET', 'https://app.jsmi.com.cn/forms/app-zs/getPath?zsh=' . $info->zsZsh, [
            'headers' => [
                'Authorization' => "Bearer " . $_GET["access_token"],
                'TENANT-ID' => '2',
            ],
        ]);
        $json = json_decode((string)$res->getBody())->data;
        if ($type) {
            $res = Http::withoutVerifying()->get('https://app.jsmi.com.cn/file/sys-file/downLoadFile//' . $info->zsZsh . '.pdf?bucket=' . $json->bucket . '&fileName=' . $json->zsdz);
            return Response::stream(
                function () use ($res) {
                    echo $res->body();
                },
                200,
                ['Content-Type' => 'application/pdf']
            );
        } else {
            $res = $client->request('GET', 'https://app.jsmi.com.cn/file/sys-file/downLoadFile//' . $info->zsZsh . '.pdf?bucket=' . $json->bucket . '&fileName=' . $json->zsdz);
            $cer = (string)$res->getBody();
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $info->zsZsh . '_' . $info->zsQjmc . '_' . (isset($info->zsCcbh) ? $info->zsCcbh == '/' ? '' : $info->zsCcbh : '') . (isset($info->zsSbbh) ? $info->zsSbbh == '/' ? '' : $info->zsSbbh : '') . '_' . $info->zsJdrq . '.pdf"',
            ];
            return Response::make($cer, 200, $headers);
        }
    }

    public function edit()
    {
        $str = isset($_GET['str']) ? $_GET['str'] : '';
        return view('jiangsu.edit', compact('str'));
    }

    public function update(Request $request)
    {
        $info = json_decode(str_replace('@', '#', $request->str));
        $arr['certificate_no'] = $info->zsZsh;
        $arr['instrument'] = $info->zsQjmc;
        $arr['model'] = $info->zsXhgg;
        $arr['number'] = (isset($info->zsCcbh) ? $info->zsCcbh == '/' ? '' : $info->zsCcbh : '') . (isset($info->zsSbbh) ? $info->zsSbbh == '/' ? '' : $info->zsSbbh : '');
        $arr['verification_date'] = $info->zsJdrq;
        $arr['remark'] = isset($request->remark) ? $request->remark : '';
        $arr['pdf'] = str_replace('@', '#', $request->str);
        return !!Jiangsu::create($arr);
    }

    //删除屏蔽
    public function destroy(Jiangsu $jiangsu)
    {
        return !!$jiangsu->delete();
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
