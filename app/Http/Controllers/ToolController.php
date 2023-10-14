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
        $type = Position::find(isset($_GET['type_id']) ? $_GET['type_id'] : Position::where('level', 2)->orderBy('sort')->first()->id);
        $types = PositionsView::where('level', 2)->get();
        $tools = ToolsView::get();
        return view('tools.tool.index', compact('type', 'types', 'tools'));
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
        $arr['requirement'] = $request->requirement;
        $arr['type_id'] = $request->type_id;
        $arr['cycle_id'] = $request->cycle_id;
        $arr['abc_id'] = $request->abc_id;
        $arr['plan_id'] = $request->plan_id;
        $arr['vulnerable'] = $request->vulnerable;
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
        $tool->requirement = $request->requirement;
        $tool->cycle_id = $request->cycle_id;
        $tool->abc_id = $request->abc_id;
        $tool->plan_id = $request->plan_id;
        $tool->vulnerable = $request->vulnerable;
        return !!$tool->save();
    }

    public function show(Tool $tool)
    {
        $numbers = NumbersView::where('tool_id', $tool->id)->get();
        return view('tools.tool.show', compact('numbers', 'tool'));
    }

    public function destroy(Tool $tool)
    {
        if (Factory::where('tool_id', $tool->id)->exists()) {
            return "量具使用中,无法删除";
        }
        return !!$tool->delete();
    }
}
