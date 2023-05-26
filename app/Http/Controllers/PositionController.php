<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\Position;
use App\Models\PositionsView;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{
    public function index()
    {
        $positions = PositionsView::get();
        $type_id = isset($_GET['type_id']) ? $_GET['type_id'] : 0;
        return view('position.index', compact('type_id', 'positions'));
    }

    public function create()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $position = Position::find($id);
        return view('position.create', compact('position'));
    }

    public function show(Position $position)
    {
        $certificates = CertificatesView::where('valid', 1)->where(function ($query) use ($position) {
            $query->where('unit1_id', $position->id)
                ->orWhere('unit2_id', $position->id)
                ->orWhere('unit3_id', $position->id)
                ->orWhere('unit4_id', $position->id)
                ->orWhere('position_id', $position->id);
        })->orderBy('instrument')->orderBy('tool_id')->get();
        return view('position.show', compact('position','certificates'));
    }

    public function store(PositionRequest $request)
    {
        $parent = Position::find($request->pid);
        $arr['name'] = $request->name;
        $arr['code'] = $request->code;
        $arr['pid'] = $request->pid;
        $arr['level'] = isset($parent->level) ? $parent->level + 1 : 1;
        $arr['sort'] = Position::max('id') + 1;
        $arr['sign'] = 0;
        return !!Position::create($arr);
    }

    public function edit(Position $position)
    {
        return view('position.edit', compact('position'));
    }

    public function update(PositionRequest $request, Position $position)
    {
        $position->name = $request->name;
        $position->code = $request->code;
        return !!$position->save();
    }

    public function destroy(Position $position)
    {
        if (Certificate::where('position_id', $position->id)->exists() || Position::where('pid', $position->id)->exists() || DB::table("admin_role_position")->where('position_id', $position->id)->exists()) {
            return "岗位使用中,无法删除";
        }
        return !!$position->delete();
    }

    public function move(Position $position, $type)
    {
        if ($type) {
            $result = Position::where('pid', "$position->pid")->where('sort', '<', $position->sort)->max('sort');
        } else {
            $result = Position::where('pid', "$position->pid")->where('sort', '>', $position->sort)->min('sort');
        }
        if ($result) {
            $position_exchange = Position::where('sort', $result)->first();
            $position_exchange->sort = $position->sort;
            $position->sort = $result;
            return !!$position->save() && !!$position_exchange->save();
        } else return false;
    }

    public function sign(Position $position)
    {
        $position->sign = !$position->sign;
        return $position->save();
    }
}
