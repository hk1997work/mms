@extends('layout.edit')
@section('content_form')
    @include('template.input',['tmp_name'=>'name','tmp_label'=>'参数名称','tmp_value'=>$parameter->name])
@endsection
