@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{$id}}">
    @include('template.input',['name'=>'name','label'=>'权限名称'])
    @include('template.input',['name'=>'description','label'=>'权限描述'])
@endsection
