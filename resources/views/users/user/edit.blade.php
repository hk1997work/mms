@extends('layout.edit')
@section('text_modal-title',"修改用户：$user->username")

@section('content_form')
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">用户名</label>
        <input type="text" name="username" id="username" class="form-control" value="{{$user->username}}" readonly>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">密码</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>
@endsection
