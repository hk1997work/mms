<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UsersView;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('users.user.index');
    }

    public function list(Request $request)
    {
        return DataTables::of(UsersView::query())
            ->editColumn('username', function ($data) {
                return $this->toValidate($data->username, $data->role);
            })
            ->editColumn('role', function ($data) {
                return $this->toBadges($data->role, 'secondary');
            })
            ->filter(function ($query) use ($request) {
                $this->toSearch($query, $request, ['username', 'role']);
            })
            ->order(function ($query) use ($request) {
                $this->toOrder($query, $request, ['id', 'username', 'role']);
            })
            ->setTotalRecords(User::count())
            ->rawColumns([1, 2])
            ->make(false);
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
        return $this->delete(User::class, $user, ['roles'], 'username');
    }

    public function role($user)
    {
        $roles = Role::orderBy('id')->get();
        $myRoles = strpos($user, ',') ? '' : User::find($user)->roles;
        return view('users.user.role', compact('roles', 'myRoles',));
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
