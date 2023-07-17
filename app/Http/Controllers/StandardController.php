<?php

namespace App\Http\Controllers;

use App\Http\Requests\StandardRequest;
use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\Standard;
use App\Models\StandardsView;

class StandardController extends Controller
{
    public function index()
    {
        $standards = StandardsView::get();
        return view('standard.index', compact('standards'));
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
        $standard = Standard::create($arr);
        return $standard->id;
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

    public function show(Standard $standard)
    {
        $tools = CertificatesView::select('instrument', 'model')->where('standard_id', 'like', '%,' . $standard->id . ',%')->groupBy('instrument', 'model')->get();
        return view('standard.show', compact('tools'));
    }

    public function destroy(Standard $standard)
    {
        if (Certificate::where('standard_id', 'like', '%,' . $standard->id . ',%')->exists() || Standard::where('pid', $standard->id)->exists()) {
            return "标准使用中,无法删除";
        }
        return !!$standard->delete();
    }
}
