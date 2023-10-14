<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateRequest;
use App\Http\Requests\NanjingRequest;
use App\Models\Certificate;
use App\Models\Factory;
use App\Models\Nanjing;
use App\Models\Number;
use App\Models\Parameter;
use App\Models\PositionsView;
use App\Models\StandardsView;
use App\Models\Tool;
use GuzzleHttp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class NanjingController extends Controller
{
    public function index()
    {
        $client = new GuzzleHttp\Client();
        #登录获取session
        $res = $client->get('http://58.213.156.66/cmiims/a/api/ajaxLogin?zMOJ96CTV+ZP2z8sI2UlA1OZyrsxdz49Ibgl7nMLnbKANNqUC+fMfTOm1E012HLJL160wCUIYjAE1uRgWUw5mAIqWVp06hAUu4nBjM9765kXkT5G81EIVT+0k3KOc0fZufz8QMUIIhmvdXPAALtpykieWUZPzPTEYwDWt1Wz/hLSvD8bcdcW6D9WLfJtGLzcdgWznY9oqtVRG2pV76SL0w==');
        $session = str_replace('; Path=/cmiims; HttpOnly', '', str_replace('cmiims.session.id=', '', $res->getHeaders()['Set-Cookie'][0]));
        $headers = ['headers' => ['Cookie' => "cmiims.session.id=$session",]];
        #获取证书清单
        $res = $client->request('GET', 'http://58.213.156.66/cmiims/a/sys/adminECertQuery/listenceInfo?Bp15yk3ja5mdrgvb99vm0wPu+D8CX+pW9Hsvf3FAnmudfLnJsjdh+T8ZvXOygoXi4gSJYHVMdRojzdteV4Uks5hwQk/+LUGqCJ8d66bdXbvRNB02z4f8uJXZhJTzajDLdgWznY9oqtVRG2pV76SL0w==', $headers);
        $result = json_decode(explode(',"firstResult"', explode('"list":', $this->nanjing_decode((string)$res->getBody()))[1])[0]);
        $nanjing = Nanjing::orderBy('verification_date')->get();
        $certificates = [];
        $sql = array_column(Certificate::select('certificate_no')->where('department_id', Parameter::where('name', '市计量院')->first()->id)->get()->toArray(), 'certificate_no');
        $certificate_no = array_diff(array_column($result, 'zsbh'), $sql, array_column($nanjing->toArray(), 'certificate_no'));
        foreach ($result as $r) {
            if (in_array($r->zsbh, $certificate_no)) {
                $certificates[] = $r;
            }
        }
        return view('nanjing.index', compact('certificates', 'nanjing'));
    }

    public function create(Request $request)
    {
        $arr = [];
        $check = explode(',', $request->id);
        $tools = Tool::orderBy('instrument')->get();
        $standards = StandardsView::where('level', 2)->orderBy('name1')->get();
        $client = new GuzzleHttp\Client();
        #登录获取session
        $res = $client->get('http://58.213.156.66/cmiims/a/api/ajaxLogin?zMOJ96CTV+ZP2z8sI2UlA1OZyrsxdz49Ibgl7nMLnbKANNqUC+fMfTOm1E012HLJL160wCUIYjAE1uRgWUw5mAIqWVp06hAUu4nBjM9765kXkT5G81EIVT+0k3KOc0fZufz8QMUIIhmvdXPAALtpykieWUZPzPTEYwDWt1Wz/hLSvD8bcdcW6D9WLfJtGLzcdgWznY9oqtVRG2pV76SL0w==');
        $session = str_replace('; Path=/cmiims; HttpOnly', '', str_replace('cmiims.session.id=', '', $res->getHeaders()['Set-Cookie'][0]));
        $headers = ['headers' => ['Cookie' => "cmiims.session.id=$session",]];
        #获取证书清单
        $res = $client->request('GET', 'http://58.213.156.66/cmiims/a/sys/adminECertQuery/listenceInfo?Bp15yk3ja5mdrgvb99vm0wPu+D8CX+pW9Hsvf3FAnmudfLnJsjdh+T8ZvXOygoXi4gSJYHVMdRojzdteV4Uks5hwQk/+LUGqCJ8d66bdXbvRNB02z4f8uJXZhJTzajDLdgWznY9oqtVRG2pV76SL0w==', $headers);
        $result = json_decode(explode(',"firstResult"', explode('"list":', $this->nanjing_decode((string)$res->getBody()))[1])[0]);
        foreach ($result as $key => $value) {
            if (in_array($value->id, $check)) {
                $value->ccbh = isset($value->ccbh) ? $value->ccbh == '/' ? '' : $value->ccbh : '';
                $value->sbbh = isset($value->sbbh) ? $value->sbbh == '/' ? '' : $value->sbbh : '';
                $number = Number::where('number', $value->ccbh . $value->sbbh)->get();
                #获取证书地址
                $str = $this->nanjing_encode('{"eCert":' . json_encode($value) . '}');
                $res = $client->request('GET', "http://58.213.156.66/cmiims/a/sys/adminECertQuery/downloadInfoByPath?$str", $headers);
                $json = json_decode($this->nanjing_decode((string)$res->getBody()));
                #获取生产厂家
                $str = $this->nanjing_encode('{"id":"' . $value->id . '"}');
                $res = $client->request('GET', "http://lims.njsjly.com/cmiims/f/sys/webQuery/inquiryByIdInfo?$str", $headers);
                $arr[$key]['factory'] = explode('","', explode('"zzcs":"', $this->nanjing_decode((string)$res->getBody()))[1])[0];
                $arr[$key]['path'] = "http://58.213.156.66" . $json->data;
                $arr[$key]['json'] = $value;
                $arr[$key]['standard'] = explode(',', $this->getStandard($request, $arr[$key]['path'])[0]);
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
        return view('nanjing.create', compact('tools', 'standards', 'arr'));
    }

    public function store(NanjingRequest $request)
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
            $certificate->department_id = Parameter::where('name', '市计量院')->first()->id;
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
                Storage::put("/public/certificate/" . $cer_id . ".pdf", file_get_contents($request->path[$key]));
                $this->pdf2jpg($cer_id);
            } else {
                return false;
            }
        }
        return true;
    }

    public function show($type)
    {
        $client = new GuzzleHttp\Client();
        #登录获取session
        $res = $client->get('http://58.213.156.66/cmiims/a/api/ajaxLogin?zMOJ96CTV+ZP2z8sI2UlA1OZyrsxdz49Ibgl7nMLnbKANNqUC+fMfTOm1E012HLJL160wCUIYjAE1uRgWUw5mAIqWVp06hAUu4nBjM9765kXkT5G81EIVT+0k3KOc0fZufz8QMUIIhmvdXPAALtpykieWUZPzPTEYwDWt1Wz/hLSvD8bcdcW6D9WLfJtGLzcdgWznY9oqtVRG2pV76SL0w==');
        $session = str_replace('; Path=/cmiims; HttpOnly', '', str_replace('cmiims.session.id=', '', $res->getHeaders()['Set-Cookie'][0]));
        $headers = ['headers' => ['Cookie' => "cmiims.session.id=$session",]];
        #获取证书地址
        $str = $this->nanjing_encode('{"eCert":' . str_replace('@', '#', $_GET['str']) . '}');
        $res = $client->request('GET', "http://58.213.156.66/cmiims/a/sys/adminECertQuery/downloadInfoByPath?$str", $headers);
        $json = json_decode($this->nanjing_decode((string)$res->getBody()));
        if ($type) {
            return redirect("http://58.213.156.66" . $json->data);
        } else {
            header('Content-Type: application/pdf');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . $json->filename . "\"");
            readfile("http://58.213.156.66" . $json->data);
        }
    }

    public function edit()
    {
        $str = isset($_GET['str']) ? $_GET['str'] : '';
        return view('nanjing.edit', compact('str'));
    }

    public function update(Request $request)
    {
        $info = json_decode(str_replace('@', '#', $request->str));
        $arr['certificate_no'] = $info->zsbh;
        $arr['instrument'] = $info->name;
        $arr['model'] = $info->xhgg;
        $arr['number'] = (isset($info->ccbh) ? $info->ccbh == '/' ? '' : $info->ccbh : '') . (isset($info->sbbh) ? $info->sbbh == '/' ? '' : $info->sbbh : '');
        $arr['verification_date'] = $info->jdrq;
        $arr['remark'] = isset($request->remark) ? $request->remark : '';
        $arr['pdf'] = str_replace('@', '#', $request->str);
        return !!Nanjing::create($arr);
    }

    //删除屏蔽
    public function destroy(Nanjing $nanjing)
    {
        return !!$nanjing->delete();
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
