<?php

namespace App\Http\Controllers;

use App\Http\Requests\NumberRequest;
use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\Number;
use App\Models\NumbersView;
use App\Models\Parameter;
use App\Models\Tool;

class NumberController extends Controller
{
    public function create()
    {
        $states = Parameter::where('pid', Parameter::where('name', '管理状态')->first()->id)->orderBy('sort')->get();
        $factory_id = $_GET['id'];
        return view('tools.number.create', compact('states','factory_id'));
    }

    public function store(NumberRequest $request)
    {
        $arr['factory_id'] = $request->factory_id;
        $arr['number'] = $request->number;
        $arr['state_id'] = $request->state_id;
        $number = Number::create($arr);
        return $number->id;
    }

    public function edit(Number $number)
    {
        $states = Parameter::where('pid', Parameter::where('name', '管理状态')->first()->id)->orderBy('sort')->get();
        return view('tools.number.edit', compact('number','states'));
    }

    public function update(NumberRequest $request, Number $number)
    {
        $number->number = $request->number;
        $number->state_id = $request->state_id;
        $state = Parameter::find($request->state_id);
        switch ($state->name) {
            case '在用':
                if (CertificatesView::where('number_id', $number->id)->where('valid', 1)->where('position', '!=', '备用')->exists()) {
                    return !!$number->save();
                } else {
                    return '无可用证书,无法修改为在用状态';
                }
            case'备用':
                if (CertificatesView::where('number_id', $number->id)->where('valid', 1)->where('position', '备用')->exists()) {
                    return !!$number->save();
                } else {
                    return '无可用证书,无法修改为备用状态';
                }
            default:
                if (CertificatesView::where('number_id', $number->id)->where('valid', 1)->exists()) {
                    return '证书使用中,无法修改状态';
                } else {
                    return !!$number->save();
                }
        }
    }

    public function show($str)
    {
        $tool = Tool::find(explode('_', $str)[0]);
        $state = explode('_', $str)[1];
        $numbers = NumbersView::where('tool_id', $tool->id)->where('state_id', Parameter::where('name', $state)->first()->id)->get();
        return view('tools.number.show', compact('numbers', 'state', 'tool'));
    }

    public function destroy(Number $number)
    {
        if (Certificate::where('number_id', $number->id)->exists()) {
            return "编号使用中,无法删除";
        }
        return !!$number->delete();
    }
}
