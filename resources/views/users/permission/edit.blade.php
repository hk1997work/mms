@extends('layout.edit')
@section('content_form')
    @include('layout.input',['name'=>'name','label'=>'权限名称','type'=>'text','value'=>$permission->name,'state'=>''])
    @include('layout.input',['name'=>'description','label'=>'权限描述','type'=>'text','value'=>$permission->description,'state'=>''])
@endsection
