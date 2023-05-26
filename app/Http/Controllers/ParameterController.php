<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParameterRequest;
use App\Models\Certificate;
use App\Models\Parameter;
use App\Models\Number;
use App\Models\ParametersView;
use App\Models\Tool;
use Illuminate\Http\Client\Request;

class ParameterController extends Controller
{
    public function index()
    {
        $parameters = ParametersView::get();
        return view('parameter.index', compact('parameters'));
    }

    public function create()
    {
        $id = $_GET['id'];
        return view('parameter.create', compact('id'));
    }

    public function store(ParameterRequest $request)
    {
        $parent = Parameter::find($request->pid);
        $arr['name'] = $request->name;
        $arr['pid'] = $request->pid;
        $arr['level'] = isset($parent->level) ? $parent->level + 1 : 1;
        $arr['sort'] = Parameter::max('sort') + 1;
        return !!Parameter::create($arr);
    }

    public function edit(Parameter $parameter)
    {
        return view('parameter.edit', compact('parameter'));
    }

    public function update(ParameterRequest $request, Parameter $parameter)
    {
        $parameter->name = $request->name;
        return !!$parameter->save();
    }

    public function destroy(ParametersView $parameter)
    {
        if (!$parameter->count == null) {
            return "参数使用中,无法删除";
        }
        return !!Parameter::where('id', $parameter->id)->delete();
    }

    public function move(Parameter $parameter, $type)
    {
        if ($type) {
            $result = Parameter::where('pid', "$parameter->pid")->where('sort', '<', $parameter->sort)->max('sort');
        } else {
            $result = Parameter::where('pid', "$parameter->pid")->where('sort', '>', $parameter->sort)->min('sort');
        }
        if ($result) {
            $parameter_exchange = Parameter::where('sort', $result)->first();
            $parameter_exchange->sort = $parameter->sort;
            $parameter->sort = $result;
            return !!$parameter->save() && !!$parameter_exchange->save();
        } else return false;
    }
}
