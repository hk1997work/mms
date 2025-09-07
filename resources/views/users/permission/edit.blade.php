@extends('layout.edit')
@section('content_form')
    @include('template.input',['tmp_name'=>'name','tmp_label'=>'权限名称','tmp_value'=>$permission->name])
    @include('template.input',['tmp_name'=>'description','tmp_label'=>'权限描述','tmp_value'=>$permission->description])
@endsection
