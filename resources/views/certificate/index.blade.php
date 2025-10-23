@extends('layout.index')
@section('content_btn')
    @if($menu=='active'||$menu=='invalid')
        @include('template.dropdown',['tmp_label'=>$type->name,'tmp_items'=>$types,'tmp_href'=>'id','tmp_field'=>'name'])
        @include('template.dropdown',['tmp_label'=>$position?$position->parent->name:'全部','tmp_items'=>$positions,'tmp_href'=>'id','tmp_field'=>'name','tmp_parent'=>$type,'tmp_path'=>'position'])
    @endif
@endsection
@section('content_table')
    <ul class="nav">
        @if($menu=='active')
            @include('template.nav-btn',['tmp_menu'=>'certificate','tmp_id'=>$type->id,'tmp_class'=>'btn-add','tmp_label'=>'增加','tmp_pos'=>'left'])
        @endif
        @include('template.nav-btn',['tmp_menu'=>'certificate','tmp_class'=>'btn-delete check-multiple','tmp_label'=>'删除'])
        @include('template.nav-btn',['tmp_menu'=>'certificate','tmp_class'=>'btn-open check-multiple','tmp_label'=>'打开'])
        @include('template.nav-btn',['tmp_menu'=>'certificate','tmp_class'=>'btn-download check-multiple','tmp_label'=>'下载'])
        @if($menu=='active')
            @include('template.nav-btn',['tmp_menu'=>'print','tmp_class'=>'btn-edit check-multiple','tmp_label'=>'标签'])
            @include('template.nav-btn',['tmp_menu'=>'receive','tmp_class'=>'btn-edit check-multiple','tmp_label'=>'领用'])
            @include('template.nav-btn',['tmp_menu'=>'supervision','tmp_class'=>'btn-show check-multiple','tmp_label'=>'监督'])
        @endif
        @include('template.nav-btn',['tmp_menu'=>'certificate','tmp_class'=>'btn-edit check-single','tmp_label'=>'修改','tmp_pos'=>'left'])
        @include('template.nav-btn',['tmp_menu'=>'certificate','tmp_class'=>'btn-show check-single','tmp_label'=>'查看','tmp_pos'=>'up'])
    </ul>
    @include('template.table',['tmp_menu'=>"certificate?id=$type->id&path=$menu".($position?"&position=$position->id":''),'tmp_headers'=>['','序号','岗位','证书编号','器具名称','规格型号','出厂编号','检定日期','有效期','检定部门','领用人','标签','备注']])
@endsection
@push('page-js-after-1')
    <script src="/admin/assets/js/pages/certificate.js"></script>
@endpush
