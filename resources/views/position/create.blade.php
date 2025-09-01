@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{isset($position->id)?$position->id:0}}">
    @include('template.input',['name'=>'name','label'=>'岗位名称'])
    @if(isset($position->level)&&$position->level==3)
        @include('template.input',['name'=>'code','label'=>'编号'])
    @endif
    @include('template.select',['name'=>'sign','label'=>'启用','selected'=>'1'])
@endsection
