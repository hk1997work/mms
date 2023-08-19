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
        $users = UsersView::get();
        return view('users.user.index', compact('users'));
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

    public function destroy(User $user)
    {
        if (DB::table("role_user")->where('user_id', $user->id)->exists()) {
            return "用户{$user->username}使用中,无法删除";
        }
        return !!$user->delete();
    }

    public function role(User $user)
    {
        $roles = Role::orderBy('id')->get();
        $myRoles = $user->roles;
        return view('users.user.role', compact('user', 'roles', 'myRoles',));
    }

    public function storeRole(User $user)
    {
        $roles = Role::find(request('roles'));
        if (isset($roles)) {
            $myRoles = $user->roles;
            $addRoles = $roles->diff($myRoles);
            foreach ($addRoles as $role) {
                $user->addRole($role);
            }
            $deleteRoles = $myRoles->diff($roles);
            foreach ($deleteRoles as $role) {
                $user->deleteRole($role);
            }
            return true;
        } else {
            return !!DB::table('role_user')->where('user_id', $user->id)->delete();
        }
    }
}
