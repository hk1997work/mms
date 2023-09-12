<?php

namespace App\Http\Controllers;

use App\Models\ChecksView;
use App\Models\Filter;
use App\Models\Position;
use Illuminate\Http\Request;


class CheckController extends Controller
{
    public function index()
    {
        $checks = ChecksView::get();
        $filters = Filter::orderBy('order')->get();
        return view("check.index", compact('checks', 'filters','types'));
    }

    public function store(Request $request)
    {
        $arr['order'] = $request->data['order'];
        $arr['event'] = $request->data['event'];
        $arr['remark'] = $request->data['remark'];
        return !!Filter::create($arr);
    }

    public function destroy(Filter $check)
    {
        return !!$check->delete();
    }
}
