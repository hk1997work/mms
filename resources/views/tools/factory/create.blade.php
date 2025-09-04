@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{$pid}}">
    <input type="hidden" name="level" value="1">
    @include('template.input',['name'=>'name','label'=>'生产厂家','value'=>$name])
    @include('template.input',['name'=>'remark','label'=>'厂家全称','value'=>$name])
@endsection
