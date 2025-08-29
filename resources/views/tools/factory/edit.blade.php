@extends('layout.edit')
@section('content_form')
    @include('template.input',['name'=>'factory','label'=>'生产厂家','value'=>$factory->factory])
    @include('template.input',['name'=>'fullname','label'=>'厂家全称','value'=>$factory->fullname])
@endsection
