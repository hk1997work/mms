@extends('layout.edit')
@section('content_form')
    @include('layout.input',['name'=>'name','label'=>'角色名称','type'=>'text','value'=>$role->name,'state'=>''])
@endsection
