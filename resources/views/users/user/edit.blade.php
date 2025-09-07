@extends('layout.edit')
@section('content_form')
    @include('template.input',['tmp_name'=>'username','tmp_label'=>'用户名','tmp_value'=>$user->username,'tmp_state'=>'readonly'])
    @include('template.input',['tmp_name'=>'password','tmp_label'=>'密码','tmp_type'=>'password'])
@endsection
