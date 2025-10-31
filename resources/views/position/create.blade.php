@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{$position->id??0}}">
    @include('template.input',['tmp_name'=>'name','tmp_label'=>'岗位名称'])
    @if(($position->level??null)==3)
        @include('template.input',['tmp_name'=>'code','tmp_label'=>'编号'])
    @endif
    @include('template.select',['tmp_name'=>'sign','tmp_label'=>'启用','tmp_selected'=>'1'])
    @include('template.select',['tmp_name'=>'check','tmp_label'=>'检查'])
@endsection
