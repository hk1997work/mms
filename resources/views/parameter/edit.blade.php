@extends('layout.edit')
@section('content_form')
    @include('template.input',['name'=>'name','label'=>'参数名称','value'=>$parameter->name])
@endsection
