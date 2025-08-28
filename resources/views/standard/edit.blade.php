@extends('layout.edit')
@section('content_form')
    @include('layout.input',['name'=>'name','label'=>'标准','type'=>'text','value'=>$standard->name,'state'=>''])
@endsection
