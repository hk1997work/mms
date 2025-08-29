@extends('layout.edit')
@section('content_form')
    @include('template.input',['name'=>'name','label'=>'角色名称','value'=>$role->name])
@endsection
