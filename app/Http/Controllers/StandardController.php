<?php

namespace App\Http\Controllers;

use App\Http\Requests\StandardRequest;
use App\Models\Standard;
use App\Models\StandardsView;

class StandardController extends Controller
{
    public function index()
    {
        return view('standard.index');
    }

    public function list()
    {
        $data = StandardsView::select('id', 'name1', 'name2', 'count', 'level')->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' id='$value[id]'>
                        <label for='$value[id]'></label>
                    </div>";
            if ($value['level'] == 1) {
                $data[$key]['name1'] = "<span class='tag btn-sm " . ($value['count'] == 0 ? 'tag-danger' : 'tag-outline-warning') . "'>$value[name1]</span>";
                $data[$key]['name2'] = "<span class='btn btn-outline-secondary btn-sm btn-add ripple' data-pos='right' data-menu='standard' data-id='$value[id]'>增加</span>";
            }
            if ($value['level'] == 2) {
                $data[$key]['name1'] = $value['name1'];
                $data[$key]['name2'] = "<span class='tag btn-sm " . ($value['count'] == 0 ? 'tag-danger' : 'tag-outline-success') . "'>$value[name2]</span>";
            }
            unset($data[$key]['level']);
        }
        return response()->json(['data' => array_map('array_values', $data)]);
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
        $id = StandardsView::selectRaw('GROUP_CONCAT(id) AS str')->whereIn('id', explode(',', $standard))->where('count', '<>', 0)->where('level', 2)->first();
        $pid = Standard::selectRaw('GROUP_CONCAT(pid) AS str')->whereIn('pid', explode(',', $standard))->whereNotIn('id', explode(',', $standard))->first();
        if ($id->str || $pid->str) {
            $id = implode(',', [$id->str, $pid->str]);
            $result = Standard::selectRaw('GROUP_CONCAT(name) AS name')->whereIn('id', array_unique(explode(',', $id)))->first();
            return $result->name . '使用中,无法删除';
        }
        return !!Standard::whereIn('id', explode(',', $standard))->delete();
    }
}
