@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{$id}}">
    @include('template.input',['name'=>'name','label'=>'参数名称'])
@endsection
