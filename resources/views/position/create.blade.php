@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{isset($position->id)?$position->id:0}}">
    @include('layout.input',['name'=>'name','label'=>'岗位名称','type'=>'text','value'=>'','state'=>''])
    @if(isset($position->level)&&$position->level==3)
        @include('layout.input',['name'=>'code','label'=>'编号','type'=>'text','value'=>'','state'=>''])
    @endif
    @include('layout.select',['name'=>'sign','label'=>'启用','selected'=>null])
@endsection
