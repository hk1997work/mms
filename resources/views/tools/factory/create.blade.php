@extends('layout.create')
@section('content_form')
    <input type="hidden" name="tool_id" value="{{$tool_id}}">
    @include('template.input',['name'=>'factory','label'=>'生产厂家','value'=>$name])
    @include('template.input',['name'=>'fullname','label'=>'厂家全称','value'=>$name])
@endsection
