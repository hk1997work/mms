<?php

namespace App\Http\Controllers;

use App\Http\Requests\NumberRequest;
use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\Number;
use App\Models\Parameter;

class NumberController extends Controller
{
    public function create()
    {
        $states = Parameter::where('pid', Parameter::where('name', '管理状态')->first()->id)->where('name','!=','在用')->orderBy('sort')->get();
        $pid = $_GET['id'];
        $name = isset($_GET['name']) ? $_GET['name'] : null;
        return view('tools.number.create', compact('states', 'pid', 'name'));
    }

    public function store(NumberRequest $request)
    {
        $arr['pid'] = $request->pid;
        $arr['name'] = $request->name;
        $arr['remark'] = $request->remark;
        $arr['level'] = $request->level;
        $arr['state_id'] = $request->state_id;
        return !!Number::create($arr);
    }

    public function edit(Number $number)
    {
        if ($number->level == 1) {
            return view('tools.factory.edit', compact('number'));
        } else {
            $states = Parameter::where('pid', Parameter::where('name', '管理状态')->first()->id)->orderBy('sort')->get();
            return view('tools.number.edit', compact('number', 'states'));
        }
    }

    public function update(NumberRequest $request, Number $number)
    {
        $number->name = $request->name;
        $number->remark = $request->remark;
        if ($number->level == 2) {
            $number->state_id = $request->state_id;
            $state = Parameter::find($request->state_id);
            if (CertificatesView::where('number_id', $number->id)->where('valid', 1)->exists()) {
                return $state->name == '在用'
                    ? !!$number->save()
                    : '证书使用中,无法修改状态';
            } else {
                return $state->name == '在用'
                    ? '无可用证书,无法修改状态'
                    : !!$number->save();
            }
        }
        return !!$number->save();
    }

    public function show($number)
    {
        $certificate = Certificate::where('number_id', $number)->orderBy('validity_date', 'desc')->first();
        return $certificate ? (new CertificateController)->show(Certificate::find($certificate->id)) : false;
    }

    public function destroy($number)
    {
        return $this->delete(Number::class, $number, ['children', 'certificates'], 'name');
    }
}
