<?php

namespace App\Http\Controllers;

use App\Http\Requests\NanjingRequest;
use App\Models\Certificate;
use App\Models\Factory;
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

class NanjingController extends Controller
{
    public function index()
    {
        return view('nanjing.index');
    }

    public function list()
    {
        $this->check();
        $result = request()->session()->get('nanjing_list');
        $nanjing = Nanjing::pluck('certificate_no')->toArray();
        $certificate = Certificate::where('department_id', Parameter::where('name', '市计量院')->first()->id)->pluck('certificate_no')->toArray();
        $list = [];
        $certificates = [];
        $i = 0;
        foreach ($result as $value) {
            if (!in_array($value->zsbh, $nanjing) && !in_array($value->zsbh, $certificate)) {
                $list[] = $value;
                $certificates[$i]['id'] = $value->zsbh;
                $certificates[$i]['jdrq'] = $value->jdrq;
                $certificates[$i]['zsbh'] = $value->zsbh;
                $certificates[$i]['name'] = $value->name;
                $certificates[$i]['xhgg'] = $value->xhgg;
                $certificates[$i]['ccbh'] = (isset($value->ccbh) ? ($value->ccbh == '/' ? '' : $value->ccbh) : '') . (isset($value->sbbh) ? ($value->sbbh == '/' ? '' : $value->sbbh) : '');
                $i = $i + 1;
            }
        }
        request()->session()->put('nanjing_certificate', $list);
        return response()->json(['data' => array_map('array_values', $certificates)]);
    }

    public function create(Request $request)
    {
        if (request()->session()->missing('nanjing_headers')) {
            $this->check();
        }
        $certificates = [];
        $check = explode(',', $request->id);
        $tools = Tool::orderBy('instrument')->get();
        $standards = StandardsView::where('level', 2)->get();
        $client = new GuzzleHttp\Client(['verify' => false]);
        $headers = request()->session()->get('nanjing_headers');
        $list = request()->session()->get('nanjing_certificate');
        foreach ($list as $key => $value) {
            if (in_array($value->zsbh, $check)) {
                #获取证书地址
                $str = $this->nanjing_encode('{"eCert":' . json_encode($value) . '}');
                $res = $client->request('GET', "http://58.213.156.66/cmiims/a/sys/adminECertQuery/downloadInfoByPath?$str", $headers);
                $json = json_decode($this->nanjing_decode((string)$res->getBody()));
                $certificates[$key]['path'] = 'http://58.213.156.66' . $json->data;
                #获取出厂编号
                $value->ccbh = isset($value->ccbh) ? $value->ccbh == '/' ? '' : $value->ccbh : '';
                $value->sbbh = isset($value->sbbh) ? $value->sbbh == '/' ? '' : $value->sbbh : '';
                $number = Number::where('number', $value->ccbh . $value->sbbh)->get();
                #获取生产厂家
                $str = $this->nanjing_encode('{"id":"' . $value->id . '"}');
                $res = $client->request('GET', "http://lims.njsjly.com/cmiims/f/sys/webQuery/inquiryByIdInfo?$str", $headers);
                if (str_contains($this->nanjing_decode((string)$res->getBody()), '"zzcs":"')) {
                    $certificates[$key]['factory'] = explode('","', explode('"zzcs":"', $this->nanjing_decode((string)$res->getBody()))[1])[0];
                } else {
                    $certificates[$key]['factory'] = '未录入生产厂家';
                }
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
        return view('nanjing.create', compact('tools', 'standards', 'certificates'));
    }

    public function store(NanjingRequest $request)
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
        return view('nanjing.edit');
    }

    public function update(Request $request, $nanjing)
    {
        if (request()->session()->missing('nanjing_session')) {
            $this->check();
        }
        $check = explode(',', $nanjing);
        $list = request()->session()->get('nanjing_certificate');
        foreach ($list as $value) {
            if (in_array($value->zsbh, $check)) {
                $arr['certificate_no'] = $value->zsbh;
                $arr['instrument'] = $value->name;
                $arr['model'] = $value->xhgg;
                $arr['number'] = (isset($value->ccbh) ? $value->ccbh == '/' ? '' : $value->ccbh : '') . (isset($value->sbbh) ? $value->sbbh == '/' ? '' : $value->sbbh : '');
                $arr['verification_date'] = $value->jdrq;
                $arr['remark'] = isset($request->remark) ? $request->remark : '';
                $arr['pdf'] = json_encode($value, JSON_UNESCAPED_UNICODE);
                Nanjing::create($arr);
            }
        }
        return true;
    }

    public function show()
    {
        return view('nanjing.show');
    }

    public function list_show()
    {
        $data = Nanjing::select('id', 'verification_date', 'certificate_no', 'instrument', 'model', 'number', 'remark')->where('remark', '!=', '安全')->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' id='$value[certificate_no]' data-url='/download_nanjing/$value[certificate_no]?type=show'>
                        <label for='$value[certificate_no]'></label>
                    </div>";
        }
        return response()->json(['data' => array_map('array_values', $data)]);
    }

    //删除屏蔽
    public function destroy($nanjing)
    {
        return !!Nanjing::whereIn('certificate_no', explode(',', $nanjing))->delete();
    }

    public function download($nanjing)
    {
        if (request()->session()->missing('nanjing_headers')) {
            $this->check();
        }
        $client = new GuzzleHttp\Client(['verify' => false]);
        $headers = request()->session()->get('nanjing_headers');
        $list = request()->session()->get('nanjing_list');
        $json = '';
        foreach ($list as $value) {
            if ($value->zsbh == $nanjing) {
                $str = $this->nanjing_encode('{"eCert":' . json_encode($value) . '}');
                $res = $client->request('GET', "http://58.213.156.66/cmiims/a/sys/adminECertQuery/downloadInfoByPath?$str", $headers);
                $json = json_decode($this->nanjing_decode((string)$res->getBody()));
            }
        }
        $res = $client->request('GET', 'http://58.213.156.66' . $json->data);
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
                $confirm_res = $client->request('GET', 'http://58.213.156.66/cmiims/a/checkList/page?' . $this->nanjing_encode('{"pageNo":1,"pageSize":50}'), $headers);
                $confirm_result = $this->nanjing_decode((string)$confirm_res->getBody());
                $confirm_list = [];
                $confirm_order = [];
                $confirm_price = 0;
                if (substr($confirm_result, 0, 7) == '{"data"') {
                    $confirm_data = json_decode('[{"' . explode('":[{"', explode('"}],"', $confirm_result)[0])[1] . '"}]');
                    foreach ($confirm_data as $value) {
                        if ($value->feeConfirm == 0) {
                            $value->check = true;
                            $confirm_list[] = $value;
                            $confirm_order[] = $value->orderNo;
                            $confirm_price = $confirm_price + $value->totalActual;
                        }
                    }
                    if ($confirm_list) {
                        $confirm_str = '{"type":"1","orders":"'
                            . implode(',', $confirm_order)
                            . '","checkedOrderList":'
                            . json_encode($confirm_list)
                            . ',"total_prince":"'
                            . number_format($confirm_price, 2)
                            . '","dw_id":"11740","apply_company":"南京巨龙钢管有限公司","contacts":"刘迪龙","telphone_num":"13155555418","tax_header":"南京巨龙钢管有限公司","tax_type":"4","tax_payer":"91320191667351423J","tax_email":"130199362@qq.com"}';
                        $client->post('http://58.213.156.66/cmiims/a/sys/payOnline/applyPayOnlineAndBatchVerify', ['body' => $this->nanjing_encode($confirm_str), 'headers' => ['Cookie' => $headers['headers']['Cookie'] . ';cmiims_login_name=13155555418;']]);
                    }
                    $get_list_res = $client->request(
                        'GET',
                        'http://58.213.156.66/cmiims/a/sys/adminECertQuery/listenceInfo?' .
                        $this->nanjing_encode(
                            '{"pageNo":1,"pageSize":"9999","orderBy":"","beginTime":"2025-01-01","searchConditionFilter":[],"mindParam1":"' .
                            $this->nanjing_encode('{"pageNo":1,"pageSize":"9999","orderBy":"","beginTime":"2025-01-01","searchConditionFilter":[]}') .
                            '","mindParam2":"1c8316e832e2a6059985ec9ad686ef77"}'
                        )
                        , $headers);
                    $get_list_result = $this->nanjing_decode((string)$get_list_res->getBody());
                    if (substr($get_list_result, 0, 7) == '{"data"') {
                        $get_list_data = json_decode('[{"' . explode('":[{"', explode('"}],"', $get_list_result)[0])[1] . '"}]');
                        request()->session()->put('nanjing_list', $get_list_data);
                        return true;
                    }
                }
            }
            #获取验证码
            $res = $client->get('http://58.213.156.66/cmiims/servlet/validateCodeServlet?' . (int)Carbon::now()->valueOf());
            $session = str_replace('; Path=/cmiims; HttpOnly', '', $res->getHeaders()['Set-Cookie'][0]);
            if (File::isDirectory("storage/" . \Auth::user()->id . "/orc") == false) {
                File::makeDirectory("storage/" . \Auth::user()->id . "/orc", 0777, true, true);
            }
            file_put_contents("storage/" . \Auth::user()->id . "/orc/1.jpg", $res->getBody());
            exec("python F:/phpstudy_pro/WWW/laravel8/python/get_validate_code.py  2>&1 " . \Auth::user()->id, $out, $status);
            if (strlen($out[count($out) - 1]) == 4) {
                $str = $this->nanjing_encode('{"userName":"13155555418","password":"Qq199362","userType":"0","validateCode":"' . $out[count($out) - 1] . '","type":"2","codeType":""}');
                #登录
                $client->post('http://58.213.156.66/cmiims/a/api/ajaxLogin', ['body' => $str, 'headers' => ['Cookie' => $session]]);
                request()->session()->put('nanjing_headers', ['headers' => ['Cookie' => $session]]);
            }
        }
        return false;
    }
}
