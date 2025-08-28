@extends('layout.create')
@section('content_form')
    <input type="hidden" name="tool_id" value="{{$tool_id}}">
    @include('layout.input',['name'=>'factory','label'=>'生产厂家','type'=>'text','value'=>$name,'state'=>''])
    @include('layout.input',['name'=>'fullname','label'=>'厂家全称','type'=>'text','value'=>$name,'state'=>''])
@endsection
