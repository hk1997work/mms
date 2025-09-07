@extends('layout.create')
@section('content_form')
    @include('template.input',['tmp_name'=>'username','tmp_label'=>'用户名'])
    @include('template.input',['tmp_name'=>'password','tmp_label'=>'密码','tmp_type'=>'password'])
@endsection
