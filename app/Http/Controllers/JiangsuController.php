<?php

namespace App\Http\Controllers;

use App\Http\Requests\JiangsuRequest;
use App\Models\Certificate;
use App\Models\Factory;
use App\Models\Jiangsu;
use App\Models\Number;
use App\Models\Parameter;
use App\Models\Position;
use App\Models\PositionsView;
use App\Models\Standard;
use App\Models\StandardsView;
use App\Models\Tool;
use Carbon\Carbon;
use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JiangsuController extends Controller
{
    public function index()
    {
        return view('jiangsu.index');
    }

    public function list()
    {
        $this->check();
        $result = request()->session()->get('jiangsu_list');
        $jiangsu = Jiangsu::pluck('certificate_no')->toArray();
        $certificate = Certificate::where('department_id', Parameter::where('name', '省计量院')->first()->id)->pluck('certificate_no')->toArray();
        $list = [];
        $certificates = [];
        $i = 0;
        foreach ($result as $value) {
            if (!in_array($value->zsZsh, $jiangsu) && !in_array($value->zsZsh, $certificate)) {
                $list[] = $value;
                $certificates[$i]['zsId'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' id='$value->zsZsh' data-url='/download_jiangsu/$value->zsZsh?type=download'>
                        <label for='$value->zsZsh'></label>
                    </div>";
                $certificates[$i]['zsJdrq'] = $value->zsJdrq;
                $certificates[$i]['zsZsh'] = $value->zsZsh;
                $certificates[$i]['zsQjmc'] = $value->zsQjmc;
                $certificates[$i]['zsXhgg'] = $value->zsXhgg;
                $certificates[$i]['zsCcbh'] = (isset($value->zsCcbh) ? ($value->zsCcbh == '/' ? '' : $value->zsCcbh) : '') . (isset($value->zsSbbh) ? ($value->zsSbbh == '/' ? '' : $value->zsSbbh) : '');
                $i = $i + 1;
            }
        }
        request()->session()->put('jiangsu_certificate', $list);
        return response()->json(['data' => array_map('array_values', $certificates)]);
    }

    public function create(Request $request)
    {
        if (request()->session()->missing('jiangsu_Authorization')) {
            $this->check();
        }
        $certificates = [];
        $check = explode(',', $request->id);
        $tools = Tool::orderBy('instrument')->get();
        $standards = StandardsView::where('level', 2)->orderBy('name1')->get();
        $client = new GuzzleHttp\Client(['verify' => false]);
        $Authorization = request()->session()->get('jiangsu_Authorization');
        $list = request()->session()->get('jiangsu_certificate');
        foreach ($list as $key => $value) {
            if (in_array($value->zsZsh, $check)) {
                #获取证书地址
                $res = $client->request('GET', 'https://app.jsmi.com.cn/forms/app-zs/getPath?zsh=' . $value->zsZsh, [
                    'headers' => [
                        'Authorization' => "Bearer $Authorization",
                        'TENANT-ID' => '2',
                    ],
                ]);
                $json = json_decode((string)$res->getBody())->data;
                $certificates[$key]['path'] = 'https://app.jsmi.com.cn/file/sys-file/downLoadFile//' . $value->zsZsh . '.pdf?bucket=' . $json->bucket . '&fileName=' . $json->zsdz;
                #获取出厂编号
                $value->zsCcbh = isset($value->zsCcbh) ? $value->zsCcbh == '/' ? '' : $value->zsCcbh : '';
                $value->zsSbbh = isset($value->zsSbbh) ? $value->zsSbbh == '/' ? '' : $value->zsSbbh : '';
                $number = Number::where('number', $value->zsCcbh . $value->zsSbbh)->get();

                $certificates[$key]['key'] = 'key' . $key;
                $certificates[$key]['json'] = $value;
                if ($number->count() == 1) {
                    $certificates[$key]['info'] = 1;
                    $factory = Factory::where('id', $number->first()->factory_id)->first();
                    $certificates[$key]['number_id'] = $number->first()->id;
                    $certificates[$key]['factory_id'] = $factory->id;
                    $certificates[$key]['tool_id'] = $factory->tool_id;
                    $certificates[$key]['numbers'] = Number::where('factory_id', $certificates[$key]['factory_id'])->get();
                    $certificates[$key]['factories'] = Factory::where('tool_id', $certificates[$key]['tool_id'])->get();
                } else {
                    $certificates[$key]['info'] = 0;
                }
            }
        }
        $result = $this->getStandards(collect($certificates)->pluck('path', 'key'));
        foreach ($result as $value) {
            $certificates[$value->key]['standard'] = explode(',', $value->standards);
            $certificates[$value->key]['category'] = $value->category;
        }
        return view('jiangsu.create', compact('tools', 'standards', 'certificates'));
    }

    public function store(JiangsuRequest $request)
    {
        foreach ($request->group as $r) {
            $tool = Tool::find($r['tool_id']);
            $cycle = Parameter::find($tool->cycle_id)->name;
            $add_date = mb_substr($cycle, 0, strlen($cycle) - 3);
            $arr['position_id'] = PositionsView::where('id4', $tool->type_id)->where('name1', '备用')->first()->id;
            $arr['sn'] = $this->getSn($arr['position_id']);
            $arr['certificate_name'] = $r['certificate_name'];
            $arr['certificate_no'] = $r['certificate_no'];
            $arr['number_id'] = $r['number_id'];
            $arr['verification_date'] = $r['verification_date'];
            if (mb_substr($cycle, -1, 1) == '天') {
                $arr['validity_date'] = date('Y-m-d', strtotime("-1 day", strtotime("+$add_date day", strtotime($arr['verification_date']))));
            }
            if (mb_substr($cycle, -1, 1) == '月') {
                $arr['validity_date'] = date('Y-m-d', strtotime("-1 day", strtotime("+$add_date month", strtotime($arr['verification_date']))));
            }
            $arr['valid'] = 1;
            $arr['category_id'] = Parameter::where('name', $r['category'])->first()->id;
            $arr['department_id'] = Parameter::where('name', '省计量院')->first()->id;
            $n = $this->getNumber($r['number_id']);
            $arr['start'] = $n->start;
            $arr['times'] = $n->times;
            $arr['remark'] = $r['remark'];
            $arr['start_date'] = $arr['verification_date'];
            $arr['end_date'] = $arr['validity_date'];
            $c = Certificate::where('number_id', $r['number_id'])->where('valid', 1)->get();
            if ($c->count() == 1 && $request->check_auto) {
                $c[0]->valid = 0;
                $c[0]->end_date = Carbon::parse(date('Y-m-d'))->min($c[0]->validity_date)->max($arr['verification_date'])->subDay();
                $c[0]->save();
                $arr['sn'] = $c[0]->sn;
                $arr['position_id'] = $c[0]->position_id;
                $arr['remark'] = $c[0]->remark;
                $arr['start_date'] = Carbon::parse(date('Y-m-d'))->min($c[0]->validity_date)->max($arr['verification_date']);
            }
            if ($certificate = Certificate::create($arr)) {
                $number = Number::find($arr['number_id']);
                $number->state_id = Parameter::where('name', '在用')->first()->id;
                $number->save();
                $standards = Standard::find($r['standard_id']);
                $certificate->standards()->sync($standards);
                Storage::put("/public/certificate/" . $certificate->id . ".pdf", file_get_contents($r['path']));
                $this->pdf2jpg($certificate->id);
            } else {
                return false;
            }
        }
        return true;
    }

    public function edit()
    {
        return view('jiangsu.edit');
    }

    public function update(Request $request, $jiangsu)
    {
        if (request()->session()->missing('jiangsu_Authorization')) {
            $this->check();
        }
        $check = explode(',', $jiangsu);
        $list = request()->session()->get('jiangsu_certificate');
        foreach ($list as $value) {
            if (in_array($value->zsZsh, $check)) {
                $arr['certificate_no'] = $value->zsZsh;
                $arr['instrument'] = $value->zsQjmc;
                $arr['model'] = $value->zsXhgg;
                $arr['number'] = (isset($value->zsCcbh) ? $value->zsCcbh == '/' ? '' : $value->zsCcbh : '') . (isset($value->zsSbbh) ? $value->zsSbbh == '/' ? '' : $value->zsSbbh : '');
                $arr['verification_date'] = $value->zsJdrq;
                $arr['remark'] = isset($request->remark) ? $request->remark : '';
                $arr['pdf'] = json_encode($value, JSON_UNESCAPED_UNICODE);
                Jiangsu::create($arr);
            }
        }
        return true;
    }

    public function show()
    {
        return view('jiangsu.show');
    }

    public function list_show()
    {
        $data = Jiangsu::select('id', 'verification_date', 'certificate_no', 'instrument', 'model', 'number', 'remark')->where('remark', '!=', '安全')->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' id='$value[certificate_no]' data-url='/download_jiangsu/$value[certificate_no]?type=download'>
                        <label for='$value[certificate_no]'></label>
                    </div>";
        }
        return response()->json(['data' => array_map('array_values', $data)]);
    }

    public function destroy($jiangsu)
    {
        return !!Jiangsu::whereIn('certificate_no', explode(',', $jiangsu))->delete();
    }

    public function download($jiangsu)
    {
        if (request()->session()->missing('jiangsu_Authorization')) {
            $this->check();
        }
        $client = new GuzzleHttp\Client(['verify' => false]);
        $Authorization = request()->session()->get('jiangsu_Authorization');
        $res = $client->request('GET', 'https://app.jsmi.com.cn/forms/app-zs/getPath?zsh=' . $jiangsu, [
            'headers' => [
                'Authorization' => "Bearer $Authorization",
                'TENANT-ID' => '2',
            ],
        ]);
        $json = json_decode((string)$res->getBody())->data;
        $res = $client->request('GET', 'https://app.jsmi.com.cn/file/sys-file/downLoadFile//' . $jiangsu . '.pdf?bucket=' . $json->bucket . '&fileName=' . $json->zsdz);
        if (isset($_GET['type'])) {
            return response((string)$res->getBody())->header('Content-Type', 'application/pdf')->header('Content-Disposition', 'attachment; filename="' . $jiangsu . '.pdf"');
        } else {
            return response($res->getBody())->header('Content-Type', 'application/pdf');
        }
    }

    public function check()
    {
        $client = new GuzzleHttp\Client(['verify' => false]);
        for ($i = 1; $i < 3; $i++) {
            try {
                if (request()->session()->has('jiangsu_Authorization')) {
                    $Authorization = request()->session()->get('jiangsu_Authorization');
                    $JSESSIONID = request()->session()->get('jiangsu_JSESSIONID');
                    $client->request('GET', 'https://serv.jsmi.com.cn/forms/zs/list?current=1&size=10&zsZsdwmc=南京巨龙钢管有限公司', [
                        'cookie' => [
                            'Authorization' => $Authorization,
                            'JSESSIONID' => $JSESSIONID,
                        ],
                        'headers' => [
                            'Authorization' => 'Bearer ' . $Authorization,
                            'TENANT-ID' => '2',
                        ],
                    ]);
                    return true;
                }
            } catch (RequestException  $exception) {
                session()->forget('jiangsu_Authorization');
            }
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
            $Authorization = json_decode((string)$res->getBody())->access_token;
            $JSESSIONID = explode(';', explode('=', $res->getHeaders()['Set-Cookie'][0])[1])[0];
            $res = $client->request('GET', 'https://serv.jsmi.com.cn/forms/zs/list?current=1&size=9999&zsZsh=&zsDdh=&zsWyxh=&zsQjmc=&zsZsdwmc=南京巨龙钢管有限公司&zsXhgg=&zsCcbh=&zsClfw=&zsZqd=&zsSbbh=&zsDyrqStart=2023-06-01', [
                'cookie' => [
                    'Authorization' => $Authorization,
                    'JSESSIONID' => $JSESSIONID,
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $Authorization,
                    'TENANT-ID' => '2',
                ],
            ]);
            $list = json_decode((string)$res->getBody())->data->records;
            request()->session()->put('jiangsu_Authorization', $Authorization);
            request()->session()->put('jiangsu_JSESSIONID', $JSESSIONID);
            request()->session()->put('jiangsu_list', $list);
        }
        return false;
    }
}
