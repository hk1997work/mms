@extends('layout.edit')
@section('content_form')
    <input type="hidden" name="level" value="1">
    @include('template.input',['name'=>'name','label'=>'生产厂家','value'=>$number->name])
    @include('template.input',['name'=>'remark','label'=>'厂家全称','value'=>$number->remark])
@endsection
