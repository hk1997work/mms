<?php

namespace App\Http\Controllers;

use App\Http\Requests\FactoryRequest;
use App\Models\Factory;
use App\Models\Number;

class FactoryController extends Controller
{
    public function create()
    {
        $tool_id = $_GET['id'];
        return view('tools.factory.create', compact('tool_id'));
    }

    public function store(FactoryRequest $request)
    {
        $arr['tool_id'] = $request->tool_id;
        $arr['factory'] = $request->factory;
        $arr['fullname'] = $request->fullname;
        $factory = Factory::create($arr);
        return $factory->id;
    }

    public function edit(Factory $factory)
    {
        return view('tools.factory.edit', compact('factory'));
    }

    public function update(FactoryRequest $request, Factory $factory)
    {
        $factory->factory = $request->factory;
        $factory->fullname = $request->fullname;
        return !!$factory->save();
    }

    public function destroy(Factory $factory)
    {
        if (Number::where('factory_id', $factory->id)->exists()) {
            return "厂家使用中,无法删除";
        }
        return !!$factory->delete();
    }
}
