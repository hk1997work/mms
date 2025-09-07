@extends('layout.edit')
@section('content_form')
    @include('template.input',['tmp_name'=>'name','tmp_label'=>'角色名称','tmp_value'=>$role->name])
@endsection
