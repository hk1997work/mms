<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParameterRequest;
use App\Models\Parameter;
use App\Models\ParametersView;

class ParameterController extends Controller
{
    public function index()
    {
        return view('parameter.index');
    }

    public function list()
    {
        $data = ParametersView::select('id', 'name1', 'name2', 'count', 'level')->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' id='$value[id]'>
                        <label for='$value[id]'></label>
                    </div>";
            if ($value['level'] == 1) {
                $data[$key]['name1'] = "<span class='tag btn-sm " . ($value['count'] == null ? 'tag-danger' : 'tag-outline-warning') . "'>$value[name1]</span>";
                $data[$key]['name2'] = "<span class='btn btn-outline-secondary btn-sm btn-add ripple' data-pos='right' data-menu='parameter' data-id='$value[id]'>增加</span>";
            }
            if ($value['level'] == 2) {
                $data[$key]['name1'] = $value['name2'];
                $data[$key]['name2'] = "<span class='tag btn-sm " . ($value['count'] == null ? 'tag-danger' : 'tag-outline-success') . "'>$value[name1]</span>";
            }
            unset($data[$key]['level']);
        }
        return response()->json(['data' => array_map('array_values', $data)]);
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
        $id = ParametersView::selectRaw('GROUP_CONCAT(id) AS str')->whereIn('id', explode(',', $parameter))->where('count', '!=', 0)->where('level', 2)->first();
        $pid = Parameter::selectRaw('GROUP_CONCAT(pid) AS str')->whereIn('pid', explode(',', $parameter))->whereNotIn('id', explode(',', $parameter))->first();
        if ($id->str || $pid->str) {
            $id = implode(',', [$id->str, $pid->str]);
            $result = Parameter::selectRaw('GROUP_CONCAT(name) AS name')->whereIn('id', array_unique(explode(',', $id)))->first();
            return $result->name . '使用中,无法删除';
        }
        return !!Parameter::whereIn('id', explode(',', $parameter))->delete();
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
        } else {
            return $type ? '已经是最顶层,无法上移' : '已经是最底层,无法下移';
        }
    }
}
