@extends('layout.edit')
@section('content_form')
    @include('template.input',['tmp_name'=>'name','tmp_label'=>'标准','tmp_value'=>$standard->name])
@endsection
