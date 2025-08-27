<?php

namespace App\Http\Controllers;

use App\Http\Requests\ToolRequest;
use App\Models\NumbersView;
use App\Models\Parameter;
use App\Models\Position;
use App\Models\Tool;
use App\Models\ToolsView;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ToolController extends Controller
{
    public function index()
    {
        $pid = Position::where('level', 1)->where('name', '备用')->first()->id;
        $type = Position::find(isset($_GET['id']) ? $_GET['id'] : Position::where('pid', $pid)->orderBy('sort')->first()->id);
        $types = Position::where('pid', $pid)->orderBy('sort')->get();
        return view('tools.tool.index', compact('type', 'types'));
    }

    public function list(Request $request)
    {
        return DataTables::of(ToolsView::where('type_id', $_GET['id']))
            ->editColumn('active', function ($data) {
                return $this->toBadges($data->active ?: null, 'success');
            })
            ->editColumn('inactive', function ($data) {
                return $this->toBadges($data->inactive ?: null, 'warning');
            })
            ->editColumn('deactive', function ($data) {
                return $this->toBadges($data->deactive ?: null, 'info');
            })
            ->editColumn('broken', function ($data) {
                return $this->toBadges($data->broken ?: null, 'danger');
            })
            ->editColumn('scrap', function ($data) {
                return $this->toBadges($data->scrap ?: null, 'dark');
            })
            ->editColumn('total', function ($data) {
                return $this->toBadges($data->total, $data->total && !$data->mistake ? 'primary' : 'danger');
            })
            ->editColumn('vulnerable', function ($data) {
                return $this->toBadges($data->vulnerable ? '易损' : null, 'danger');
            })
            ->filter(function ($query) use ($request) {
                $this->toSearch($query, $request, ['instrument', 'model', 'limit', 'accuracy', 'cycle', 'abc', 'requirement']);
            })
            ->order(function ($query) use ($request) {
                $this->toOrder($query, $request, ['id', 'instrument', 'model', 'limit', 'accuracy', 'cycle', 'abc', 'active', 'inactive', 'deactive', 'broken', 'scrap', 'total', 'vulnerable', 'requirement']);
            })
            ->setTotalRecords(Tool::where('type_id', $_GET['id'])->count())
            ->rawColumns([7, 8, 9, 10, 11, 12, 13])
            ->removeColumn('type_id', 'mistake')
            ->make(false);
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

    public function list_show(Request $request)
    {
        $id = $_GET['id'];
        return DataTables::of(NumbersView::where('tool_id', $id))
            ->editColumn('name1', function ($data) {
                return $this->toLevel($data, 1, 'name', 'number', 'warning', !$data->mistake, $data->state_id ? 2 : 1);
            })
            ->editColumn('name2', function ($data) {
                return $this->toLevel($data, 2, 'name', 'number', 'success', !$data->mistake, $data->state_id ? 2 : 1);
            })
            ->editColumn('state', function ($data) {
                $tag = '';
                switch ($data->state) {
                    case '在用':
                        $tag = "success";
                        break;
                    case '待检':
                        $tag = "warning";
                        break;
                    case '封存':
                        $tag = "info";
                        break;
                    case '损坏':
                        $tag = "danger";
                        break;
                    case '报废':
                        $tag = "dark";
                        break;
                }
                return $this->toBadges($data->state, $tag);
            })
            ->editColumn('times', function ($data) {
                return $this->toBadges($data->times, $data->times ? 'secondary' : 'danger');
            })
            ->filter(function ($query) use ($request) {
                $this->toSearch($query, $request, ['factory', 'number', 'state']);
            })
            ->rawColumns([1, 2, 3, 4])
            ->make(false);
    }

    public function destroy($tool)
    {
        return $this->delete(Tool::class, $tool, ['factory'], 'instrument');
    }
}
