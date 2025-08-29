@extends('layout.create')
@section('content_form')
    <input type="hidden" name="factory_id" value="{{$factory_id}}">
    @include('template.input',['name'=>'number','label'=>'出厂编号','value'=>$name])
    @include('template.select',['name'=>'state_id','label'=>'管理状态','selected'=>'待检','items'=>$states->where('name','!=','在用'),'value'=>'id','validate'=>'name','field'=>'name'])
@endsection

