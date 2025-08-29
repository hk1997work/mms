@extends('layout.edit')
@section('content_form')
    @include('template.input',['name'=>'name','label'=>'标准','value'=>$standard->name])
@endsection
