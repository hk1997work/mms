@extends('layout.edit')
@section('content_form')
    @include('template.input',['name'=>'number','label'=>'出厂编号','value'=>$number->number])
    @include('template.select',['name'=>'state_id','label'=>'管理状态','selected'=>$number->state_id,'items'=>$states,'value'=>'id','validate'=>'id','field'=>'name'])
@endsection
