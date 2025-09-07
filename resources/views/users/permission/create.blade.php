@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{$id}}">
    @include('template.input',['tmp_name'=>'name','tmp_label'=>'权限名称'])
    @include('template.input',['tmp_name'=>'description','tmp_label'=>'权限描述'])
@endsection
