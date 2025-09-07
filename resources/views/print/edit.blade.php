@extends('layout.submit')
@section('content_form')
    @include('template.input',['tmp_name'=>'row','tmp_label'=>'起始行(1-10)','tmp_value'=>'1','tmp_type'=>'number'])
    @include('template.input',['tmp_name'=>'column','tmp_label'=>'起始列(1-5)','tmp_value'=>'1','tmp_type'=>'number'])
    @include('template.checkbox',['tmp_name'=>'check_position','tmp_label'=>'打印岗位信息'])
@endsection
