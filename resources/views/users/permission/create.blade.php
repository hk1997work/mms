@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{$id}}">
    @include('layout.input',['name'=>'name','label'=>'权限名称','type'=>'text','value'=>'','state'=>''])
    @include('layout.input',['name'=>'description','label'=>'权限描述','type'=>'text','value'=>'','state'=>''])
@endsection
