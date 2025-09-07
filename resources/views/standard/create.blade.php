@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{$id}}">
    @include('template.input',['tmp_name'=>'name','tmp_label'=>'标准'])
@endsection
