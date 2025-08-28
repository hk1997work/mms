@extends('layout.edit')
@section('content_form')
    @include('layout.input',['name'=>'username','label'=>'用户名','type'=>'text','value'=>$user->username,'state'=>'readonly'])
    @include('layout.input',['name'=>'password','label'=>'密码','type'=>'password','value'=>'','state'=>''])
@endsection
