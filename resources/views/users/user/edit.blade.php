@extends('layout.edit')
@section('content_form')
    @include('template.input',['name'=>'username','label'=>'用户名','value'=>$user->username,'state'=>'readonly'])
    @include('template.input',['name'=>'password','label'=>'密码','type'=>'password'])
@endsection
