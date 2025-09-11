@extends('layout.show')
@section('content_title')
    @include('template.nav-tab',['tmp_name'=>'number','tmp_label'=>"{$tool->instrument}详情",'tmp_active'=>true])
@endsection
@section('content_form')
    <ul class="nav">
        @include('template.nav-btn',['tmp_label'=>'增加','tmp_menu'=>'factory','tmp_class'=>'btn-add','tmp_id'=>$tool->id])
        @include('template.nav-btn',['tmp_label'=>'删除','tmp_menu'=>'number','tmp_class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['tmp_label'=>'修改','tmp_menu'=>'number','tmp_class'=>'btn-edit check-single'])
        @include('template.nav-btn',['tmp_label'=>'查看','tmp_menu'=>'number','tmp_class'=>'btn-show check-single','tmp_pos'=>'up'])
    </ul>
    @include('template.table',['tmp_menu'=>"tool_show?id=$tool->id",'tmp_fields'=>['','生产厂家','出厂编号','使用状态','使用次数','备注'],'tmp_id'=>'off-sidebar','tmp_class'=>'table-tree table-all'])
    @include('template.sidebar-btn')
@endsection
