@extends('layout.create')
@section('content_form')
    @include('template.input',['name'=>'username','label'=>'用户名'])
    @include('template.input',['name'=>'password','label'=>'密码','type'=>'password'])
@endsection
