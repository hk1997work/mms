<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use App\Models\PermissionsView;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{

    public function index()
    {
        return view('users.permission.index');
    }

    public function list()
    {
        $data = PermissionsView::select('id', 'description1', 'description2', 'description3', 'name', 'role', 'level')->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' id='$value[id]'>
                        <label for='$value[id]'></label>
                    </div>";
            if ($value['level'] == 1) {
                $data[$key]['description1'] = "<span class='tag btn-sm " . ($value['role'] == '' ? 'tag-danger' : 'tag-outline-warning') . "'>$value[description1]</span>";
                $data[$key]['description2'] = "<span class='btn btn-outline-secondary btn-sm btn-add ripple' data-pos='right' data-menu='permission' data-id='$value[id]'>增加</span>";
            }
            if ($value['level'] == 2) {
                $data[$key]['description1'] = $value['description2'];
                $data[$key]['description2'] = "<span class='tag btn-sm " . ($value['role'] == '' ? 'tag-danger' : 'tag-outline-success') . "'>$value[description1]</span>";
                $data[$key]['description3'] = "<span class='btn btn-outline-secondary btn-sm btn-add ripple' data-pos='right' data-menu='permission' data-id='$value[id]'>增加</span>";
            }
            if ($value['level'] == 3) {
                $data[$key]['description1'] = $value['description3'];
                $data[$key]['description3'] = "<span class='tag btn-sm " . ($value['role'] == '' ? 'tag-danger' : 'tag-outline-info') . "'>$value[description1]</span>";
            }
            unset($data[$key]->level);
        }
        return response()->json(['data' => array_map('array_values', $data)]);
    }

    public function create()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        return view('users.permission.create', compact('id'));
    }

    public function store(PermissionRequest $request)
    {
        $parent = Permission::find($request->pid);
        $arr['name'] = $request->name;
        $arr['description'] = $request->description;
        $arr['pid'] = $request->pid;
        $arr['level'] = isset($parent->level) ? $parent->level + 1 : 1;
        $arr['sort'] = Permission::max('sort') + 1;
        return !!Permission::create($arr);
    }

    public function edit(Permission $permission)
    {
        return view('users.permission.edit', compact('permission'));
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $permission->name = $request->name;
        $permission->description = $request->description;
        return !!$permission->save();
    }

    public function destroy($permission)
    {
        $role = DB::table("permission_role")->selectRaw('GROUP_CONCAT(permission_id) AS str')->whereIn('permission_id', explode(',', $permission))->first();
        $pid = Permission::selectRaw('GROUP_CONCAT(pid) AS str')->whereIn('pid', explode(',', $permission))->whereNotIn('id', explode(',', $permission))->first();
        if ($role->str || $pid->str) {
            $id = implode(',', [$role->str, $pid->str]);
            $result = Permission::selectRaw('GROUP_CONCAT(description) AS name')->whereIn('id', array_unique(explode(',', $id)))->first();
            return $result->name . '使用中,无法删除';
        }
        return !!Permission::whereIn('id', explode(',', $permission))->delete();
    }

    public function move(Permission $permission, $type)
    {
        if ($type) {
            $result = Permission::where('pid', "$permission->pid")->where('sort', '<', $permission->sort)->max('sort');
        } else {
            $result = Permission::where('pid', "$permission->pid")->where('sort', '>', $permission->sort)->min('sort');
        }
        if ($result) {
            $permission_exchange = Permission::where('sort', $result)->first();
            $permission_exchange->sort = $permission->sort;
            $permission->sort = $result;
            return !!$permission->save() && !!$permission_exchange->save();
        } else return $type ? '已经是最顶层,无法上移' : '已经是最底层,无法下移';
    }
}
