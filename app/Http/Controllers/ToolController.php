<?php

namespace App\Http\Controllers;

use App\Http\Requests\ToolRequest;
use App\Models\Factory;
use App\Models\NumbersView;
use App\Models\Parameter;
use App\Models\Position;
use App\Models\PositionsView;
use App\Models\Tool;
use App\Models\ToolsView;

class ToolController extends Controller
{
    public function index()
    {
        $type = Position::find(isset($_GET['id']) ? $_GET['id'] : Position::where('level', 2)->orderBy('sort')->first()->id);
        $types = Position::where('level', 2)->get();
        return view('tools.tool.index', compact('type', 'types'));
    }

    public function list()
    {
        $type_id = isset($_GET['id']) ? $_GET['id'] : Position::where('level', 2)->orderBy('sort')->first()->id;
        $data = ToolsView::select('id', 'instrument', 'model', 'limit', 'accuracy', 'cycle', 'abc', 'active', 'standby', 'inactive', 'deactive', 'broken', 'scrap', 'total', 'vulnerable', 'requirement', 'mistake')->where('type_id', $type_id)->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' id='$value[id]'>
                        <label for='$value[id]'></label>
                    </div>";
            $data[$key]['active'] = $value['active'] ? "<span class='tag btn-sm tag-outline-success'>$value[active]</span>" : '';
            $data[$key]['standby'] = $value['standby'] ? "<span class='tag btn-sm tag-outline-info'>$value[standby]</span>" : '';
            $data[$key]['inactive'] = $value['inactive'] ? "<span class='tag btn-sm tag-outline-warning'>$value[inactive]</span>" : '';
            $data[$key]['deactive'] = $value['deactive'] ? "<span class='tag btn-sm tag-outline-primary'>$value[deactive]</span>" : '';
            $data[$key]['broken'] = $value['broken'] ? "<span class='tag btn-sm tag-outline-danger'>$value[broken]</span>" : '';
            $data[$key]['scrap'] = $value['scrap'] ? "<span class='tag btn-sm tag-outline-dark'>$value[scrap]</span>" : '';
            $data[$key]['total'] = $value['mistake'] ? "<span class='tag btn-sm tag-danger'>$value[total]</span>" : ($value['total'] ? "<span class='tag btn-sm tag-outline-secondary'>$value[total]</span>" : '');
            $data[$key]['vulnerable'] = $value['vulnerable'] ? "<span class='tag btn-sm tag-danger'>易损</span>" : '';
        }
        return response()->json(['data' => array_map('array_values', $data)]);
    }

    public function create()
    {
        $type_id = $_GET['id'];
        $cycles = Parameter::where('pid', Parameter::where('name', '检定周期')->first()->id)->orderBy('sort')->get();
        $abcs = Parameter::where('pid', Parameter::where('name', 'ABC类')->first()->id)->orderBy('sort')->get();
        $plans = Parameter::where('pid', Parameter::where('name', '检定计划')->first()->id)->orderBy('sort')->get();
        return view('tools.tool.create', compact('type_id', 'cycles', 'abcs', 'plans'));
    }

    public function store(ToolRequest $request)
    {
        $arr['instrument'] = $request->instrument;
        $arr['model'] = $request->model;
        $arr['limit'] = $request->limit;
        $arr['accuracy'] = $request->accuracy;
        $arr['type_id'] = $request->type_id;
        $arr['cycle_id'] = $request->cycle_id;
        $arr['abc_id'] = $request->abc_id;
        $arr['plan_id'] = $request->plan_id;
        $arr['vulnerable'] = $request->vulnerable;
        $arr['requirement'] = $request->requirement;
        return !!Tool::create($arr);
    }

    public function edit(Tool $tool)
    {
        $cycles = Parameter::where('pid', Parameter::where('name', '检定周期')->first()->id)->orderBy('sort')->get();
        $abcs = Parameter::where('pid', Parameter::where('name', 'ABC类')->first()->id)->orderBy('sort')->get();
        $plans = Parameter::where('pid', Parameter::where('name', '检定计划')->first()->id)->orderBy('sort')->get();
        return view('tools.tool.edit', compact('tool', 'cycles', 'abcs', 'plans'));
    }

    public function update(ToolRequest $request, Tool $tool)
    {
        $tool->instrument = $request->instrument;
        $tool->model = $request->model;
        $tool->limit = $request->limit;
        $tool->accuracy = $request->accuracy;
        $tool->cycle_id = $request->cycle_id;
        $tool->abc_id = $request->abc_id;
        $tool->plan_id = $request->plan_id;
        $tool->vulnerable = $request->vulnerable;
        $tool->requirement = $request->requirement;
        return !!$tool->save();
    }

    public function show(Tool $tool)
    {
        return view('tools.tool.show', compact('tool'));
    }

    public function list_show()
    {
        $id = $_GET['id'];
        $data = NumbersView::select('id', 'factory', 'number', 'state', 'mistake')->where('tool_id', $id)->get()->toArray();
        foreach ($data as $key => $value) {
            if ($data[$key]['number']) {
                switch ($data[$key]['state']) {
                    case '在用':
                        $tag = "success";
                        break;
                    case '备用':
                        $tag = "info";
                        break;
                    case '待检':
                        $tag = "warning";
                        break;
                    case '封存':
                        $tag = "primary";
                        break;
                    case '损坏':
                        $tag = "danger";
                        break;
                    case '报废':
                        $tag = "dark";
                        break;
                }
                $data[$key]['state'] = "<span class='tag btn-sm tag-$tag'>$value[state]</span>";
                $data[$key]['number'] = $value['mistake'] == 1 ? "<span class='tag btn-sm tag-danger'>$value[number]</span>" : $value['number'];
                $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' data-menu='number' id='$value[id]cb'>
                        <label for='$value[id]cb'></label>
                    </div>";
            } else {
                $data[$key]['factory'] = $value['mistake'] == 1 ? "<span class='tag btn-sm tag-danger'>$value[factory]</span>" : $value['factory'];
                $data[$key]['number'] = "<span class='btn btn-outline-secondary btn-sm btn-add ripple' data-pos='right' data-menu='number' data-id='$value[id]'>增加</span>";
                $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' data-menu='factory' data-hide='btn-show' id='$value[id]cb'>
                        <label for='$value[id]cb'></label>
                    </div>";
            }

            unset($data[$key]['mistake']);
        }
        return response()->json(['data' => array_map('array_values', $data)]);
    }

    public function destroy($tool)
    {
        $id = Factory::selectRaw('GROUP_CONCAT(tool_id) AS str')->whereIn('tool_id', explode(',', $tool))->first();
        if ($id->str) {
            $result = Tool::selectRaw('GROUP_CONCAT(instrument) AS name')->whereIn('id', explode(',', $id->str))->first();
            return $result->name . '使用中,无法删除';
        } else {
            return !!Tool::whereIn('id', explode(',', $tool))->delete();
        }
    }
}
