@extends('layout.index')
@section('content_btn')
    @if($menu=='active'||$menu=='invalid')
        @include('template.dropdown',['label'=>$type->name,'items'=>$types,'id'=>'id','field'=>'name'])
        @include('template.dropdown',['label'=>$position?$position->parent->name:'全部','items'=>$positions,'id'=>'id','field'=>'name','parent'=>$type,'path'=>'position'])
    @endif
@endsection
@section('content_table')
    <ul class="nav">
        @if($menu=='active')
            @include('template.nav-btn',['label'=>'增加','menu'=>'certificate','class'=>'btn-add','id'=>$type->id,'pos'=>'left'])
        @endif
        @include('template.nav-btn',['label'=>'删除','menu'=>'certificate','class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['label'=>'打开','menu'=>'certificate','class'=>'btn-open check-multiple'])
        @include('template.nav-btn',['label'=>'下载','menu'=>'certificate','class'=>'btn-download check-multiple'])
        @if($menu=='active')
            @include('template.nav-btn',['label'=>'打印标签','menu'=>'print','class'=>'btn-edit check-multiple'])
            @include('template.nav-btn',['label'=>'监督检查','menu'=>'supervision','class'=>'btn-show check-multiple'])
        @endif
        @include('template.nav-btn',['label'=>'修改','menu'=>'certificate','class'=>'btn-edit check-single','pos'=>'left'])
        @include('template.nav-btn',['label'=>'查看','menu'=>'certificate','class'=>'btn-show check-single','pos'=>'up'])
    </ul>
    @include('template.table',['menu'=>"certificate?id=$type->id&path=$menu".($position?'&position='.$position->id:''),'fields'=>['','序号','岗位','证书编号','器具名称','规格型号','出厂编号','检定日期','有效期','检定部门','备注']])
@endsection
@push('page-js-after-1')
    <script src="/admin/assets/js/pages/certificate.js"></script>
@endpush
