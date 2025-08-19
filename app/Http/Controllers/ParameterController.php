<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParameterRequest;
use App\Models\Parameter;
use App\Models\ParametersView;
use Illuminate\Http\Request;

class ParameterController extends Controller
{
    public function index()
    {
        return view('parameter.index');
    }

    public function list(Request $request)
    {
        return Parameter::getList($request);
    }

    public function create()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        return view('parameter.create', compact('id'));
    }

    public function store(ParameterRequest $request)
    {
        $parent = Parameter::find($request->pid);
        $arr['name'] = $request->name;
        $arr['pid'] = $request->pid;
        $arr['level'] = isset($parent->level) ? $parent->level + 1 : 1;
        $arr['sort'] = Parameter::max('sort') + 1;
        $arr['sort_str'] = isset($parent->sort_str) ? $parent->sort_str . ',' . $arr['sort'] : $arr['sort'];
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

    public function destroy($parameter)
    {
        $ids = explode(',', $parameter);
        $result = Parameter::whereIn('id', $ids)
            ->where(function ($query) {
                $query->has('children')
                    ->orHas('category')
                    ->orHas('department')
                    ->orHas('cycle')
                    ->orHas('abc')
                    ->orHas('plan')
                    ->orHas('state');
            })
            ->pluck('name')
            ->implode(',');
        if ($result) {
            return $result . '使用中,无法删除';
        } else {
            return !!Parameter::whereIn('id', $ids)->delete();
        }
    }

    public function move(Parameter $parameter, $type)
    {
        return $this->moveUpDown($parameter, $type);
    }
}
