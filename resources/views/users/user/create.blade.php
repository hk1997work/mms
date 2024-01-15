@extends('layout.create')
@section('content_form')
    <div class="col-12 div-username">
        <div class="sidebar-heading mt-3 mb-2">用户名</div>
        <input type="text" name="username" class="form-control">
    </div>
    <div class="col-12 div-password">
        <div class="sidebar-heading mt-3 mb-2">密码</div>
        <input type="password" name="password" class="form-control">
    </div>
@endsection
