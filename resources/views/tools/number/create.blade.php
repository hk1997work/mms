@extends('layout.create')
@section('content_form')
    <input type="hidden" name="factory_id" value="{{$factory_id}}">
    @include('layout.input',['name'=>'number','label'=>'出厂编号','type'=>'text','value'=>'','state'=>''])
    @include('layout.select',['name'=>'state_id','label'=>'管理状态','selected'=>'待检','items'=>$states->where('name','!=','在用'),'id'=>'id','valid'=>'name','field'=>'name'])
@endsection

