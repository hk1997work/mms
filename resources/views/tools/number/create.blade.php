@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{$pid}}">
    <input type="hidden" name="level" value="2">
    @include('template.input',['name'=>'name','label'=>'出厂编号','value'=>$name])
    @include('template.select',['name'=>'state_id','label'=>'管理状态','selected'=>'待检','items'=>$states->where('name','!=','在用'),'value'=>'id','validate'=>'name','field'=>'name'])
    @include('template.input',['name'=>'remark','label'=>'备注'])
@endsection

