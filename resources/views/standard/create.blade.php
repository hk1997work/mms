@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{$id}}">
    @include('template.input',['name'=>'name','label'=>'标准'])
@endsection
