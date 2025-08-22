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
                $this->toOrder($query, $request);
            })
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

    public function list_show()
    {
        $id = $_GET['id'];
        $data = NumbersView::select('id', 'factory', 'number', 'state', 'times', 'remark', 'mistake')->where('tool_id', $id)->get()->toArray();
        foreach ($data as $key => $value) {
            if ($data[$key]['number']) {
                switch ($data[$key]['state']) {
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
                        $tag = "primary";
                        break;
                }
                $data[$key]['state'] = "<span class='tag btn-sm tag-$tag'>$value[state]</span>";
                $data[$key]['number'] = $value['mistake'] == 1 ? "<span class='tag btn-sm tag-danger'>$value[number]</span>" : $value['number'];
                $data[$key]['remark'] = $value['remark'];
                $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' data-menu='number' id='$value[id]cb'>
                        <label for='$value[id]cb'></label>
                    </div>";
            } else {
                $data[$key]['factory'] = $value['mistake'] == 1 ? "<span class='tag btn-sm tag-danger'>$value[factory]</span>" : $value['factory'];
                $data[$key]['number'] = "<span class='btn btn-outline-secondary btn-sm btn-add ripple' data-pos='right' data-menu='number' data-id='$value[id]'>增加</span>";
                $data[$key]['remark'] = '';
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
        return $this->delete(Tool::class, $tool, ['factory'], 'instrument');
    }
}
