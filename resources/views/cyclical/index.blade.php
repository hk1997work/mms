@extends('layout.index')
@section('content_btn')
    @include('template.dropdown',['tmp_label'=>'','tmp_items'=>[],'tmp_href'=>'','tmp_field'=>''])
    @include('template.nav-btn',['tmp_menu'=>'cyclical','tmp_class'=>'submit-download','tmp_label'=>'下载'])
    <form id="form-download" action="/cyclical" method="post">
        {{csrf_field()}}
        <input type="hidden" id="date-download" name="date">
    </form>
@endsection
@section('content_table')
    @include('template.table',['tmp_menu'=>"cyclical?id={$units->where('date',date('Y-m'))->first()->position4}&date=".date('Y-m'),'tmp_headers'=>['序号','岗位','器具名称','规格型号','出厂编号','测量范围','精确度','生产厂家','检定日期','有效期','检定周期','备注'],'tmp_class'=>'table-tree table-all table-unselect'])
    <div id="timeline"></div>
@endsection
@push('page-js-after-1')
    <script>
        var units = {!! $units->toJson() !!};
    </script>
    <script src="/admin/assets/js/pages/cyclical.js"></script>
@endpush
