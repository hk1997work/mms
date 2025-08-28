@extends('layout.edit')
@section('content_form')
    @include('layout.input',['name'=>'name','label'=>'岗位名称','type'=>'text','value'=>$position->name,'state'=>''])
    @if($position->level==4)
        @include('layout.input',['name'=>'code','label'=>'编号','type'=>'text','value'=>$position->code,'state'=>''])
    @endif
    @include('layout.select',['name'=>'sign','label'=>'启用','selected'=>$position->sign])
@endsection
