@extends('layout.edit')
@section('content_form')
    @include('template.input',['tmp_name'=>'receiver','tmp_label'=>'领用人'])
    @include('template.input',['tmp_name'=>'receiving_date','tmp_class'=>'single-date','tmp_label'=>'领用日期'])
@endsection
<script src="/admin/assets/js/pages/control.js"></script>