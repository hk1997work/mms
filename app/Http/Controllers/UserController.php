<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UsersView;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return view('users.user.index');
    }

    public function list()
    {
        $data = UsersView::select('id', 'username', 'role')->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['id'] = "<div class='styled-checkbox'>
                        <input type='checkbox' name='cb' class='cb' id='$value[id]'>
                        <label for='$value[id]'></label>
                    </div>";
            if ($value['role'] == '') {
                $data[$key]['username'] = "<div class='text-danger'>$value[username]</div>";
            }
        }
        return response()->json(['data' => array_map('array_values', $data)]);
    }

    public function create()
    {
        return view('users.user.create');
    }

    public function store(UserRequest $request)
    {
        $user['username'] = $request->username;
        $user['password'] = bcrypt($request->password);
        return !!User::create($user);
    }

    public function edit(User $user)
    {
        return view('users.user.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $user->password = bcrypt($request->password);
        return !!$user->save();
    }

    public function destroy($user)
    {
        $id = DB::table("role_user")->selectRaw('GROUP_CONCAT(user_id) AS str')->whereIn('user_id', explode(',', $user))->first();
        if ($id->str) {
            $result = User::selectRaw('GROUP_CONCAT(username) AS name')->whereIn('id', explode(',', $id->str))->first();
            return $result->name . '使用中,无法删除';
        } else {
            return !!User::whereIn('id', explode(',', $user))->delete();
        }
    }

    public function role($user)
    {
        $roles = Role::orderBy('id')->get();
        $myRoles = strpos($user, ',') ? '' : User::find($user)->roles;
        return view('users.user.role', compact('user', 'roles', 'myRoles',));
    }

    public function storeRole($user)
    {
        $roles = Role::find(request('role'));
        $users = User::find(explode(',', $user));
        foreach ($users as $u) {
            $u->roles()->sync($roles);
        }
        return true;
    }
}
