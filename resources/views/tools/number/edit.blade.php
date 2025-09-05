@extends('layout.edit')
@section('content_form')
    <input type="hidden" name="level" value="2">
    @include('template.input',['name'=>'name','label'=>'出厂编号','value'=>$number->name])
    @include('template.select',['name'=>'state_id','label'=>'管理状态','selected'=>$number->state_id,'items'=>$states,'value'=>'id','validate'=>'id','field'=>'name'])
    @include('template.input',['name'=>'remark','label'=>'备注','value'=>$number->remark])
@endsection
