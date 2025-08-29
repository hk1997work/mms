@extends('layout.edit')
@section('content_form')
    @include('template.input',['name'=>'name','label'=>'岗位名称','value'=>$position->name])
    @if($position->level==4)
        @include('template.input',['name'=>'code','label'=>'编号','value'=>$position->code])
    @endif
    @include('template.select',['name'=>'sign','label'=>'启用','selected'=>$position->sign])
@endsection
