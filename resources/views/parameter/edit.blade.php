@extends('layout.edit')
@section('content_form')
    @include('layout.input',['name'=>'name','label'=>'参数名称','type'=>'text','value'=>$parameter->name,'state'=>''])
@endsection
