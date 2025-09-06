@extends('layout.edit')
@section('content_form')
    <input type="hidden" name="level" value="2">
    @include('template.input',['name'=>'name','label'=>'出厂编号','value'=>$number->name])
    @include('template.select',['name'=>'state_id','label'=>'管理状态','items'=>$states,'value'=>'id','field'=>'name','selected'=>$number->state_id,'validate'=>'id'])
    @include('template.input',['name'=>'remark','label'=>'备注','value'=>$number->remark])
@endsection
