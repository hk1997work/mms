<?php

namespace App\Http\Controllers;

use App\Http\Requests\NumberRequest;
use App\Models\Number;

class FactoryController extends Controller
{
    public function create()
    {
        $pid = $_GET['id'];
        $name = isset($_GET['name']) ? $_GET['name'] : null;
        return view('tools.factory.create', compact('pid', 'name'));
    }

    public function store(NumberRequest $request)
    {
        $arr['pid'] = $request->pid;
        $arr['name'] = $request->name;
        $arr['remark'] = $request->remark;
        $arr['level'] = $request->level;
        return !!Number::create($arr);
    }
}
