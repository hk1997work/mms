<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function login()
    {
        $user = request(['username', 'password']);
        if (\Auth::attempt($user)) {
            return redirect('/active');
        }
        return back()->withErrors("用户名密码错误");
    }

    public function logout()
    {
        \Auth::logout();
        return redirect('/');
    }
}
