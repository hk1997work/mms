@extends('layout.edit')
@section('content_form')
    @include('template.input',['tmp_name'=>'remark','tmp_label'=>'备注'])
@endsection
