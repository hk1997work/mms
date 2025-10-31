<?php

namespace App\Http\Controllers;

use App\Http\Requests\StandardRequest;
use App\Models\Standard;
use App\Models\StandardsView;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StandardController extends Controller
{
    public function index()
    {
        return view('standard.index');
    }

    public function list(Request $request)
    {
        return DataTables::of(StandardsView::query())
            ->editColumn('name1', function ($data) {
                return $this->toLevel($data, 1, 'name', 'standard', 'warning', $data->count);
            })
            ->editColumn('name2', function ($data) {
                return $this->toLevel($data, 2, 'name', 'standard', 'success', $data->count);
            })
            ->editColumn('department', function ($data) {
                return $this->toBadges($data->department, 'secondary');
            })
            ->editColumn('count', function ($data) {
                $count = $data->level == 1 ? '' : $data->count;
                return $this->toBadges($count, 'secondary');
            })
            ->filter(function ($query) use ($request) {
                $this->toSearch($query, $request, ['name1', 'name2']);
            })
            ->setTotalRecords(Standard::count())
            ->removeColumn('level')
            ->rawColumns([1, 2, 3, 4])
            ->make(false);
    }

    public function create()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        return view('standard.create', compact('id'));
    }

    public function store(StandardRequest $request)
    {
        $parent = Standard::find($request->pid);
        $arr['name'] = $request->name;
        $arr['pid'] = $request->pid;
        $arr['level'] = isset($parent->level) ? $parent->level + 1 : 1;
        return !!Standard::create($arr);
    }

    public function edit(Standard $standard)
    {
        return view('standard.edit', compact('standard'));
    }

    public function update(StandardRequest $request, Standard $standard)
    {
        $standard->name = $request->name;
        return !!$standard->save();
    }

    public function destroy($standard)
    {
        return $this->delete(Standard::class, $standard, ['children', 'certificate'], 'name');
    }
}
