@extends('layout.edit')
@section('content_form')
    @include('template.input',['name'=>'name','label'=>'权限名称','value'=>$permission->name])
    @include('template.input',['name'=>'description','label'=>'权限描述','value'=>$permission->description])
@endsection
