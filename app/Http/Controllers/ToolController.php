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
        $type = Position::find($_GET['id'] ?? Position::where('pid', $pid)->orderBy('sort')->first()->id);
        $types = Position::where('pid', $pid)->orderBy('sort')->get();
        return view('tools.tool.index', compact('type', 'types'));
    }

    public function list(Request $request)
    {
        return DataTables::of(ToolsView::where('type_id', $_GET['id']))
            ->editColumn('active', function ($data) {
                return $this->toBadge($data->active, 'success', 1);
            })
            ->editColumn('inactive', function ($data) {

                return $this->toBadge($data->inactive, 'warning', 1);
            })
            ->editColumn('deactive', function ($data) {
                return $this->toBadge($data->deactive, 'info', 1);
            })
            ->editColumn('broken', function ($data) {
                return $this->toBadge($data->broken, 'danger', 1);
            })
            ->editColumn('scrap', function ($data) {
                return $this->toBadge($data->scrap, 'dark', 1);
            })
            ->editColumn('total', function ($data) {
                return $this->toValidateBadge($data->total, 'primary', !$data->mistake);
            })
            ->editColumn('vulnerable', function ($data) {
                return $this->toBadge($data->vulnerable, 'danger', 1, '易损');
            })
            ->filter(function ($query) use ($request) {
                $this->toSearch($query, $request, ['instrument', 'model', 'limit', 'accuracy', 'cycle', 'abc', 'vulnerable', 'requirement'], 'vulnerable', ['', '易损']);
            })
            ->order(function ($query) use ($request) {
                $this->toOrder($query, $request, ['id', 'instrument', 'model', 'limit', 'accuracy', 'cycle', 'abc', 'active', 'inactive', 'deactive', 'broken', 'scrap', 'total', 'vulnerable', 'requirement']);
            })
            ->setTotalRecords(Tool::where('type_id', $_GET['id'])->count())
            ->rawColumns([7, 8, 9, 10, 11, 12, 13])
            ->removeColumn('type_id', 'mistake', 'plan')
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

    public function listShow(Request $request)
    {
        $id = $_GET['id'];
        return DataTables::of(NumbersView::select('id', 'name1', 'name2', 'state', 'count', 'remark', 'level', 'mistake')->where('pid', $id))
            ->editColumn('name1', function ($data) {
                return $this->toLevel($data, 1, 'name', 'number', 'secondary', !$data->mistake);
            })
            ->editColumn('name2', function ($data) {
                return $this->toLevel($data, 2, 'name', 'number', 'secondary', !$data->mistake);
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
                return $this->toBadge($data->state, $tag);
            })
            ->editColumn('count', function ($data) {
                return $this->toValidateBadge($data->count, 'secondary', $data->count);
            })
            ->removeColumn('level', 'mistake')
            ->rawColumns([1, 2, 3, 4])
            ->make(false);
    }

    public function destroy($tool)
    {
        return $this->delete(Tool::class, $tool, ['factories'], 'instrument');
    }
}
