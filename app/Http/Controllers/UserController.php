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
                return $data->role
                    ? e($data->username)
                    : "<span class='badge bg-danger-subtle border border-danger-subtle text-danger-emphasis'>" . e($data->username) . "</span>";
            })
            ->addColumn('role', function ($data) {
                $arrays = explode(',', $data->role);
                $badges = [];
                foreach ($arrays as $array) {
                    $badges[] = "<span class='badge bg-secondary-subtle border border-secondary-subtle text-secondary-emphasis'>" . e($array) . "</span>";
                }
                return implode(' ', $badges);
            })
            ->filter(function ($query) use ($request) {
                if (!empty($search = $request->search['value'])) {
                    $terms = explode(' ', $search);
                    $query->where(function ($q) use ($terms) {
                        foreach ($terms as $term) {
                            $q->where(function ($innerQ) use ($term) {
                                $innerQ->where('username', 'like', "%$term%")
                                    ->orWhere('role', 'like', "%$term%");
                            });
                        }
                    });
                }
            })
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
        $ids = explode(',', $user);
        $result = User::whereIn('id', $ids)
            ->has('roles')
            ->pluck('username')
            ->implode(',');
        if ($result) {
            return $result . '使用中,无法删除';
        } else {
            return !!User::whereIn('id', $ids)->delete();
        }
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
