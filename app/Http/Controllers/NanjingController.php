<?php

namespace App\Http\Controllers;

use App\Http\Requests\NanjingRequest;
use App\Models\Certificate;
use App\Models\Nanjing;
use App\Models\Number;
use App\Models\Parameter;
use App\Models\PositionsView;
use App\Models\Standard;
use App\Models\StandardsView;
use App\Models\Tool;
use Carbon\Carbon;
use GuzzleHttp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class NanjingController extends Controller
{
    public function index()
    {
        return view('nanjing.index');
    }

    public function list()
    {
        $result = $this->check();
        $nanjing = Nanjing::pluck('certificate_no');
        $certificate = Certificate::where('department_id', Parameter::where('name', '市计量院')->first()->id)->pluck('certificate_no');
        $list = [];
        $certificates = [];
        foreach ($result as $value) {
            if (!$nanjing->contains($value->zsbh) && !$certificate->contains($value->zsbh)) {
                $value->ccbh = ((!isset($value->ccbh) || $value->ccbh == '/') ? '' : $value->ccbh) . ((!isset($value->sbbh) || $value->sbbh == '/') ? '' : $value->sbbh);
                $list[] = $value;
                $certificates[] = [$value->zsbh, $value->jdrq, $value->zsbh, $value->name, $value->xhgg, $value->ccbh,];
            }
        }
        request()->session()->put('nanjing_certificate', $list);
        return response()->json(['data' => $certificates]);
    }

    public function create(Request $request)
    {
        $client = new GuzzleHttp\Client(['verify' => false]);
        $headers = request()->session()->get('nanjing_headers');
        $list = request()->session()->get('nanjing_certificate');
        $certificates = [];
        $ids = explode(',', $request->id);
        $tools = Tool::orderBy('instrument')->get();
        $standards = StandardsView::where('level', 2)->get();
        foreach ($list as $key => $value) {
            if (in_array($value->zsbh, $ids)) {
                #获取证书地址
                $str = $this->nanjing_encode('{"eCert":' . json_encode($value) . '}');
                $res = $client->request('GET', "https://www.njsnjl.cn/cmiims/a/sys/adminECertQuery/downloadInfoByPath?$str", $headers);
                $json = json_decode($this->nanjing_decode((string)$res->getBody()));
                $certificates[$key] = (array)$value;
                $certificates[$key]['key'] = 'key' . $key;
                $certificates[$key]['path'] = "https://www.njsnjl.cn$json->data";
                #获取生产厂家
                $str = $this->nanjing_encode("{'id':'$value->id'}");
                $res = $client->request('GET', "https://www.njsnjl.cn/cmiims/f/sys/webQuery/inquiryByIdInfo?$str", $headers);
                $json = json_decode($this->nanjing_decode((string)$res->getBody()));
                $certificates[$key]['factory'] = $json->certificateInfo->zzcs;

                $numbers = Number::where('name', $value->ccbh)->get();
                if ($numbers->count() == 1) {
                    $factory = Number::find($numbers->first()->pid);
                    $certificates[$key]['number_id'] = $numbers->first()->id;
                    $certificates[$key]['factory_id'] = $factory->id;
                    $certificates[$key]['tool_id'] = $factory->pid;
                    $certificates[$key]['numbers'] = $this->getNumbers($factory->id, $_GET['number_id'] = 0);
                    $certificates[$key]['factories'] = $this->getFactories($factory->pid);
                } else {
                    $certificates[$key]['number_id'] = null;
                    $certificates[$key]['factory_id'] = null;
                    $certificates[$key]['tool_id'] = null;
                    $certificates[$key]['numbers'] = null;
                    $certificates[$key]['factories'] = null;
                }
            }
        }
        $result = $this->getStandards(collect($certificates)->pluck('path', 'key'));
        foreach ($result as $value) {
            $certificates[$value->key]['standard'] = explode(',', $value->standards);
            $certificates[$value->key]['category'] = $value->category;
        }
        return view('nanjing.create', compact('tools', 'standards', 'certificates'));
    }

    public function store(NanjingRequest $request)
    {
        foreach ($request->group as $r) {
            $tool = Tool::find($r['tool_id']);
            $cycle = Parameter::find($tool->cycle_id)->name;
            $add_date = mb_substr($cycle, 0, strlen($cycle) - 3);
            $arr['position_id'] = PositionsView::where('id3', $tool->type_id)->where('name1', '备用')->first()->id;
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
            $arr['department_id'] = Parameter::where('name', '市计量院')->first()->id;
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
                Storage::put("/public/certificate/$certificate->id.pdf", file_get_contents($r['path']));
                $this->pdf2jpg($certificate->id);
            } else {
                return false;
            }
        }
        return true;
    }

    public function edit()
    {
        return view('nanjing.edit');
    }

    public function update(Request $request, $nanjing)
    {
        if (request()->session()->missing('nanjing_certificate')) {
            return '登录失效,请刷新';
        }
        $ids = explode(',', $nanjing);
        $list = request()->session()->get('nanjing_certificate');
        foreach ($list as $value) {
            if (in_array($value->zsbh, $ids)) {
                $arr['certificate_no'] = $value->zsbh;
                $arr['instrument'] = $value->name;
                $arr['model'] = $value->xhgg;
                $arr['number'] = $value->ccbh;
                $arr['verification_date'] = $value->jdrq;
                $arr['remark'] = $request->remark ?? '';
                $arr['pdf'] = json_encode($value);
                Nanjing::create($arr);
            }
        }
        return true;
    }

    public function show()
    {
        return view('nanjing.show');
    }

    public function listShow()
    {
        $query = Nanjing::select('id', 'verification_date', 'certificate_no', 'instrument', 'model', 'number', 'remark')->where('remark', '!=', '安全');
        return DataTables::of($query)
            ->editColumn('id', function ($data) {
                return $data->certificate_no;
            })
            ->make(false);
    }

    public function destroy($nanjing)
    {
        return !!Nanjing::whereIn('certificate_no', explode(',', $nanjing))->delete();
    }

    public function download($nanjing)
    {
        if (request()->session()->missing('nanjing_headers')) {
            return '登录失效,请刷新';
        }
        $client = new GuzzleHttp\Client(['verify' => false]);
        $headers = request()->session()->get('nanjing_headers');
        $list = request()->session()->get('nanjing_certificate');
        $json = '';
        foreach ($list as $value) {
            if ($value->zsbh == $nanjing) {
                $str = $this->nanjing_encode('{"eCert":' . json_encode($value) . '}');
                $res = $client->request('GET', "https://www.njsnjl.cn/cmiims/a/sys/adminECertQuery/downloadInfoByPath?$str", $headers);
                $json = json_decode($this->nanjing_decode((string)$res->getBody()));
            }
        }
        $res = $client->request('GET', 'https://www.njsnjl.cn' . $json->data);
        if (isset($_GET['type'])) {
            return response($res->getBody())->header('Content-Type', 'application/pdf');
        } else {
            return response((string)$res->getBody())->header('Content-Type', 'application/pdf')->header('Content-Disposition', 'attachment; filename="' . $nanjing . '.pdf"');
        }
    }

    public function check()
    {
        $client = new GuzzleHttp\Client(['verify' => false]);
        for ($i = 1; $i < 3; $i++) {
            if (request()->session()->has('nanjing_headers')) {
                $headers = request()->session()->get('nanjing_headers');
                $list_str = [
                    "pageNo" => "1",
                    "pageSize" => "9999",
                    "orderBy" => "",
                    "beginTime" => "2025-01-01",
                    "searchConditionFilter" => [],
                ];
                $list_json = json_encode($list_str);
                $list_str['mindParam1'] = $this->nanjing_encode($list_json);
                $list_str['mindParam2'] = md5($list_json . "&njmindToken");
                $get_list_res = $client->request('GET', 'https://www.njsnjl.cn/cmiims/a/sys/adminECertQuery/listenceInfo?' . $this->nanjing_encode(json_encode($list_str)), $headers);
                $get_list_result = json_decode($this->nanjing_decode((string)$get_list_res->getBody()));
                if ($get_list_result->success) {
                    return $get_list_result->data->list;
                }
            }
            #获取验证码
            $res = $client->get('https://www.njsnjl.cn/cmiims/servlet/validateCodeServlet?' . (int)Carbon::now()->valueOf());
            $session = $res->getHeaders()['Set-Cookie'][0];
            if (File::isDirectory("storage/" . \Auth::user()->id . "/orc") == false) {
                File::makeDirectory("storage/" . \Auth::user()->id . "/orc", 0777, true, true);
            }
            file_put_contents("storage/" . \Auth::user()->id . "/orc/1.jpg", $res->getBody());
            exec("python F:/phpstudy_pro/WWW/laravel8/python/get_validate_code.py  2>&1 " . \Auth::user()->id, $out, $status);
            if (strlen($out[count($out) - 1]) == 4) {
                $login_str = json_encode([
                    "userName" => "13155555418",
                    "password" => "Qq199362",
                    "userType" => "0",
                    "validateCode" => $out[count($out) - 1],
                    "type" => "3",
                    "codeType" => "",
                ]);
                $client->post('https://www.njsnjl.cn/cmiims/a/api/ajaxLogin', [
                    'body' => $this->nanjing_encode($login_str),
                    'headers' => ['Cookie' => $session],
                ]);
                request()->session()->put('nanjing_headers', ['headers' => ['Cookie' => $session]]);
            }
        }
        return false;
    }
}
