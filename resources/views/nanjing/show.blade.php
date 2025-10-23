@extends('layout.show')
@section('content_title')
    @include('template.nav-tab',['tmp_name'=>'nanjing','tmp_label'=>'屏蔽列表','tmp_active'=>true])
@endsection
@section('content_form')
    <ul class="nav">
        @include('template.nav-btn',['tmp_menu'=>'nanjing','tmp_class'=>'btn-delete check-multiple','tmp_label'=>'删除'])
        @include('template.nav-btn',['tmp_menu'=>'nanjing','tmp_class'=>'btn-open check-multiple','tmp_label'=>'打开'])
        @include('template.nav-btn',['tmp_menu'=>'nanjing','tmp_class'=>'btn-download check-single','tmp_label'=>'下载'])
    </ul>
    @include('template.table',['tmp_menu'=>"nanjing_show",'tmp_headers'=>['','检定日期','证书编号','器具名称','规格型号','出厂编号','备注'],'tmp_id'=>'off-sidebar','tmp_class'=>'table-all'])
    @include('template.sidebar-btn')
@endsection
