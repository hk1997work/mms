@extends('layout.show')
@section('content_title')
    @include('template.nav-tab',['tmp_name'=>'jiangsu','tmp_label'=>'屏蔽列表','tmp_active'=>true])
@endsection
@section('content_form')
    <ul class="nav">
        @include('template.nav-btn',['tmp_label'=>'删除','tmp_menu'=>'jiangsu','tmp_class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['tmp_label'=>'打开','tmp_menu'=>'jiangsu','tmp_class'=>'btn-open check-multiple'])
        @include('template.nav-btn',['tmp_label'=>'下载','tmp_menu'=>'jiangsu','tmp_class'=>'btn-download check-single'])
    </ul>
    @include('template.table',['tmp_menu'=>"jiangsu_show",'tmp_fields'=>['','检定日期','证书编号','器具名称','规格型号','出厂编号','备注'],'tmp_table_id'=>'off-sidebar','tmp_class'=>'table-all'])
    @include('template.sidebar-btn')
@endsection
