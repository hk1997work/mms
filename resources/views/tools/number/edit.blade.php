@extends('layout.edit')
@section('content_form')
    <input type="hidden" name="level" value="2">
    @include('template.input',['tmp_name'=>'name','tmp_label'=>'出厂编号','tmp_value'=>$number->name])
    @include('template.select',['tmp_name'=>'state_id','tmp_label'=>'管理状态','tmp_items'=>$states,'tmp_selected'=>$number->state_id,'tmp_validate'=>'id','tmp_value'=>'id','tmp_field'=>'name'])
    @include('template.input',['tmp_name'=>'remark','tmp_label'=>'备注','tmp_value'=>$number->remark])
@endsection
