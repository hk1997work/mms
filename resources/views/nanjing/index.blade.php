@extends('layout.index')
@section('content_btn')
    @include('template.nav-btn',['tmp_label'=>'屏蔽列表','tmp_menu'=>'nanjing','tmp_class'=>'btn-show','tmp_pos'=>'left','tmp_id'=>'true'])
@endsection
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_label'=>'录入','tmp_menu'=>'nanjing','tmp_class'=>'btn-add check-multiple','tmp_pos'=>'left'])
        @include('template.nav-btn',['tmp_label'=>'屏蔽','tmp_menu'=>'nanjing','tmp_class'=>'btn-edit check-multiple'])
        @include('template.nav-btn',['tmp_label'=>'打开','tmp_menu'=>'nanjing','tmp_class'=>'btn-open check-multiple'])
        @include('template.nav-btn',['tmp_label'=>'下载','tmp_menu'=>'nanjing','tmp_class'=>'btn-download check-multiple'])
    </ul>
    @include('template.table',['tmp_menu'=>'nanjing','tmp_fields'=>['','检定日期','证书编号','器具名称','规格型号','出厂编号'],'tmp_class'=>'table-all'])
@endsection
@push('page-js-after-1')
    <script src="/admin/assets/js/pages/nanjing.js"></script>
@endpush