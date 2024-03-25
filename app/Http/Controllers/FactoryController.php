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
        $name = isset($_GET['name']) ? $_GET['name'] : null;
        return view('tools.factory.create', compact('tool_id', 'name'));
    }

    public function store(FactoryRequest $request)
    {
        $arr['tool_id'] = $request->tool_id;
        $arr['factory'] = $request->factory;
        $arr['fullname'] = $request->fullname;
        return !!Factory::create($arr);
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

    public function destroy($factory)
    {
        $id = Number::selectRaw('GROUP_CONCAT(factory_id) AS str')->whereIn('factory_id', explode(',', $factory))->first();
        if ($id->str) {
            $result = Factory::selectRaw('GROUP_CONCAT(factory) AS name')->whereIn('id', explode(',', $id->str))->first();
            return $result->name . '使用中,无法删除';
        } else {
            return !!Factory::whereIn('id', explode(',', $factory))->delete();
        }
    }
}
