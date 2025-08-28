@extends('layout.edit')
@section('content_form')
    @include('layout.input',['name'=>'number','label'=>'出厂编号','type'=>'text','value'=>$number->number,'state'=>''])
    @include('layout.select',['name'=>'state_id','label'=>'管理状态','selected'=>$number->state_id,'items'=>$states,'id'=>'id','valid'=>'name','field'=>'name'])
@endsection
