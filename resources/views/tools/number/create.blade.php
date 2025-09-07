@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{$pid}}">
    <input type="hidden" name="level" value="2">
    @include('template.input',['tmp_name'=>'name','tmp_label'=>'出厂编号','tmp_value'=>$name])
    @include('template.select',['tmp_name'=>'state_id','tmp_label'=>'管理状态','tmp_items'=>$states->where('name','!=','在用'),'tmp_value'=>'id','tmp_field'=>'name','tmp_selected'=>'待检','tmp_validate'=>'name'])
    @include('template.input',['tmp_name'=>'remark','tmp_label'=>'备注'])
@endsection

