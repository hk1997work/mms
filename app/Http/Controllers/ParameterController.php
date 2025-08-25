<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParameterRequest;
use App\Models\Parameter;
use App\Models\ParametersView;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ParameterController extends Controller
{
    public function index()
    {
        return view('parameter.index');
    }

    public function list(Request $request)
    {

        return DataTables::of(ParametersView::query())
            ->editColumn('name1', function ($data) {
                return $this->toLevel($data, 1, 'name', 'parameter', 'warning', $data->count);
            })
            ->editColumn('name2', function ($data) {
                return $this->toLevel($data, 2, 'name', 'parameter', 'success', $data->count);
            })
            ->editColumn('count', function ($data) {
                return $this->toBadges($data->count, 'secondary');
            })
            ->filter(function ($query) use ($request) {
                $this->toSearch($query, $request, ['name1', 'name2']);
            })
            ->setTotalRecords(Parameter::count())
            ->removeColumn('level')
            ->rawColumns([1, 2, 3])
            ->make(false);
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
        return $this->delete(Parameter::class, $parameter, ['children', 'category', 'department', 'cycle', 'abc', 'plan', 'state'], 'name');
    }

    public function move(Parameter $parameter, $type)
    {
        return $this->moveUpDown($parameter, $type);
    }
}
