@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{$id}}">
    @include('layout.input',['name'=>'name','label'=>'参数名称','type'=>'text','value'=>'','state'=>''])
@endsection
