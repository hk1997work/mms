@extends('layout.edit')
@section('content_form')
    @include('template.input',['tmp_name'=>'receiver','tmp_label'=>'领用人'])
    @include('template.input',['tmp_name'=>'receiving_date','tmp_label'=>'领用日期'])
@endsection
<script>
    $('[name="receiving_date"]').daterangepicker({
        singleDatePicker: true,
        autoApply: true,
        parentEl: $('.off-sidebar-container'),
        container: '[name="receiving_date"]',
    });
</script>