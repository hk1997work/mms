@extends('layout.edit')
@section('content_form')
    @include('layout.input',['name'=>'factory','label'=>'生产厂家','type'=>'text','value'=>$factory->factory,'state'=>''])
    @include('layout.input',['name'=>'fullname','label'=>'厂家全称','type'=>'text','value'=>$factory->fullname,'state'=>''])
@endsection
