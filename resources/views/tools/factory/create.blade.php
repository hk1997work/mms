@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{$pid}}">
    <input type="hidden" name="level" value="1">
    @include('template.input',['tmp_name'=>'name','tmp_label'=>'生产厂家','tmp_value'=>$name])
    @include('template.input',['tmp_name'=>'remark','tmp_label'=>'厂家全称','tmp_value'=>$name])
@endsection
