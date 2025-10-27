@extends('layout.index')
@section('content_btn')
    @include('template.nav-btn',['tmp_menu'=>'nanjing','tmp_id'=>'true','tmp_class'=>'btn-show','tmp_label'=>'屏蔽列表','tmp_pos'=>'left'])
@endsection
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_menu'=>'nanjing','tmp_class'=>'btn-add check-multiple','tmp_label'=>'录入','tmp_pos'=>'left'])
        @include('template.nav-btn',['tmp_menu'=>'nanjing','tmp_class'=>'btn-edit check-multiple','tmp_label'=>'屏蔽'])
        @include('template.nav-btn',['tmp_menu'=>'nanjing','tmp_class'=>'btn-open check-multiple','tmp_label'=>'打开'])
        @include('template.nav-btn',['tmp_menu'=>'nanjing','tmp_class'=>'btn-download check-multiple','tmp_label'=>'下载'])
    </ul>
    @include('template.table',['tmp_menu'=>'nanjing','tmp_headers'=>['','检定日期','证书编号','器具名称','规格型号','出厂编号'],'tmp_class'=>'table-all'])
@endsection
@push('page-js-after-1')
    <script src="/admin/assets/js/pages/organ.js"></script>
@endpush