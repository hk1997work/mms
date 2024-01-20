<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Models\Certificate;
use App\Models\CertificatesView;
use App\Models\Position;
use App\Models\PositionsView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{
    public function index()
    {
        return view('position.index');
    }

    public function list()
    {
        $data = PositionsView::select('id', 'name1', 'name2', 'name3', 'name4', 'name5', 'code', 'count', 'total', 'level', 'sign',)->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' id='$value[id]'>
                        <label for='$value[id]'></label>
                    </div>";
            if ($value['level'] == 1) {
                $data[$key]['name1'] = "<span class='tag btn-sm " . ($value['total'] == 0 && $value['sign'] == 0 ? ' tag-danger' : 'tag-outline-primary') . "'>$value[name1]</span>";
                $data[$key]['name2'] = "<span class='btn btn-outline-secondary btn-sm btn-add ripple' data-pos='right' data-menu='position' data-id='$value[id]'>增加</span>";
                $data[$key]['count'] = "<span class='tag btn-sm " . ($value['sign'] ? ' tag-outline-danger' : 'tag-outline-secondary') . "'>$value[count]" . ($value['count'] == $value['total'] ? '' : '/' . $value['total']) . "</span>";;
            }
            if ($value['level'] == 2) {
                $data[$key]['name1'] = $value['name2'];
                $data[$key]['name2'] = "<span class='tag btn-sm " . ($value['total'] == 0 && $value['sign'] == 0 ? ' tag-danger' : 'tag-outline-primary') . "'>$value[name1]</span>";
                $data[$key]['name3'] = "<span class='btn btn-outline-secondary btn-sm btn-add ripple' data-pos='right' data-menu='position' data-id='$value[id]'>增加</span>";
                $data[$key]['count'] = "<span class='tag btn-sm " . ($value['sign'] ? ' tag-outline-danger' : 'tag-outline-secondary') . "'>$value[count]" . ($value['count'] == $value['total'] ? '' : '/' . $value['total']) . "</span>";;
            }
            if ($value['level'] == 3) {
                $data[$key]['name1'] = $value['name3'];
                $data[$key]['name2'] = $value['name2'];
                $data[$key]['name3'] = "<span class='tag btn-sm " . ($value['total'] == 0 && $value['sign'] == 0 ? ' tag-danger' : 'tag-outline-warning') . "'>$value[name1]</span>";
                $data[$key]['name4'] = "<span class='btn btn-outline-secondary btn-sm btn-add ripple' data-pos='right' data-menu='position' data-id='$value[id]'>增加</span>";
                $data[$key]['count'] = "<span class='tag btn-sm " . ($value['sign'] ? ' tag-outline-danger' : 'tag-outline-secondary') . "'>$value[count]" . ($value['count'] == $value['total'] ? '' : '/' . $value['total']) . "</span>";;
            }
            if ($value['level'] == 4) {
                $data[$key]['name1'] = $value['name4'];
                $data[$key]['name2'] = $value['name3'];
                $data[$key]['name3'] = $value['name2'];
                $data[$key]['name4'] = "<span class='tag btn-sm " . ($value['total'] == 0 && $value['sign'] == 0 ? ' tag-danger' : 'tag-outline-success') . "'>$value[name1]</span>";
                $data[$key]['name5'] = "<span class='btn btn-outline-secondary btn-sm btn-add ripple' data-pos='right' data-menu='position' data-id='$value[id]'>增加</span>";
                $data[$key]['count'] = "<span class='tag btn-sm " . ($value['sign'] ? ' tag-outline-danger' : 'tag-outline-secondary') . "'>$value[count]" . ($value['count'] == $value['total'] ? '' : '/' . $value['total']) . "</span>";;
            }
            if ($value['level'] == 5) {
                $data[$key]['name1'] = $value['name5'];
                $data[$key]['name2'] = $value['name4'];
                $data[$key]['name3'] = $value['name3'];
                $data[$key]['name4'] = $value['name2'];
                $data[$key]['name5'] = "<span class='tag btn-sm " . ($value['total'] == 0 && $value['sign'] == 0 ? ' tag-danger' : 'tag-outline-info') . "'>$value[name1]</span>";
                $data[$key]['code'] = "<span class='tag btn-sm tag-info'>$value[code]</span>";
                $data[$key]['count'] = "<span class='tag btn-sm " . ($value['sign'] ? ' tag-outline-danger' : 'tag-outline-secondary') . "'>$value[count]" . ($value['count'] == $value['total'] ? '' : '/' . $value['total']) . "</span>";;
            }
            unset($data[$key]['level']);
            unset($data[$key]['total']);
        }
        return response()->json(['data' => array_map('array_values', $data)]);
    }

    public function create()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $position = Position::find($id);
        return view('position.create', compact('position'));
    }

    public function show(Position $position)
    {
        return view('position.show', compact('position'));
    }

    public function list_show()
    {
        $id = $_GET['id'];
        $data = CertificatesView::selectRaw('MAX(id) AS id,`order`,position,instrument,MAX(valid) AS valid,MIN(verification_date) AS verification_date,COUNT(*) AS count')
            ->where('position', '<>', '备用')->where(function ($query) use ($id) {
                $query->where('unit1_id', $id)
                    ->orWhere('unit2_id', $id)
                    ->orWhere('unit3_id', $id)
                    ->orWhere('unit4_id', $id)
                    ->orWhere('position_id', $id);
            })->groupBy('order', 'position', 'position_id', 'instrument')->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' id='$value[id]cb'>
                        <label for='$value[id]cb'></label>
                    </div>";
            $data[$key]['valid'] = "<span class='tag btn-sm " . ($value['valid'] ? "tag-success'>有效" : "tag-danger'>失效") . "</span>";
        }
        return response()->json(['data' => array_map('array_values', $data)]);
    }

    public function store(PositionRequest $request)
    {
        $parent = Position::find($request->pid);
        $arr['name'] = $request->name;
        $arr['code'] = $request->code;
        $arr['pid'] = $request->pid;
        $arr['level'] = isset($parent->level) ? $parent->level + 1 : 1;
        $arr['sort'] = Position::max('id') + 1;
        $arr['sign'] = $request->sign;
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
        $position->sign = $request->sign;
        return !!$position->save();
    }

    public function destroy($position)
    {
        $certificate = Certificate::selectRaw('GROUP_CONCAT(position_id) AS str')->whereIn('position_id', explode(',', $position))->first();
        $pid = Position::selectRaw('GROUP_CONCAT(pid) AS str')->whereIn('pid', explode(',', $position))->whereNotIn('id', explode(',', $position))->first();
        if ($certificate->str || $pid->str) {
            $id = implode(',', [$certificate->str, $pid->str]);
            $result = Position::selectRaw('GROUP_CONCAT(name) AS name')->whereIn('id', array_unique(explode(',', $id)))->first();
            return $result->name . '使用中,无法删除';
        }
        return !!Position::whereIn('id', explode(',', $position))->delete();
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
        } else return $type ? '已经是最顶层,无法上移' : '已经是最底层,无法下移';
    }

    public function sn(Certificate $certificate)
    {
        return view('position.sn', compact('certificate'));
    }

    public function updateSn(Request $request, Certificate $certificate)
    {
        if (Certificate::where('position_id', $certificate->position_id)->where('sn', $request->sn)->exists()) {
            return '序号重复';
        }
        return !!Certificate::where('position_id', $certificate->position_id)->where('sn', $certificate->sn)->update(['sn' => $request->sn]);
    }

    public function moveSn(Certificate $certificate, $type)
    {
        if ($type) {
            $result = Certificate::where('position_id', "$certificate->position_id")->where('sn', '<', $certificate->sn)->max('sn');
        } else {
            $result = Certificate::where('position_id', "$certificate->position_id")->where('sn', '>', $certificate->sn)->min('sn');
        }
        if ($result) {
            $a = Certificate::where('position_id', $certificate->position_id)->where('sn', $certificate->sn)->get();
            $b = Certificate::where('position_id', $certificate->position_id)->where('sn', $result)->get();
            foreach ($a as $itemA) {
                $itemA->sn = $result;
                $itemA->save();
            }
            foreach ($b as $itemB) {
                $itemB->sn = $certificate->sn;
                $itemB->save();
            }
            return true;
        } else return $type ? '已经是最顶层,无法上移' : '已经是最底层,无法下移';
    }
}
