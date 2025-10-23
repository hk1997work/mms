@extends('layout.show')
@section('content_title')
    @include('template.nav-tab',['tmp_name'=>'number','tmp_label'=>"{$tool->instrument}详情",'tmp_active'=>true])
@endsection
@section('content_form')
    <ul class="nav">
        @include('template.nav-btn',['tmp_menu'=>'factory','tmp_id'=>$tool->id,'tmp_class'=>'btn-add','tmp_label'=>'增加'])
        @include('template.nav-btn',['tmp_menu'=>'number','tmp_class'=>'btn-delete check-multiple','tmp_label'=>'删除'])
        @include('template.nav-btn',['tmp_menu'=>'number','tmp_class'=>'btn-edit check-single','tmp_label'=>'修改'])
        @include('template.nav-btn',['tmp_menu'=>'number','tmp_class'=>'btn-show check-single','tmp_label'=>'查看','tmp_pos'=>'up'])
    </ul>
    @include('template.table',['tmp_menu'=>"tool_show?id=$tool->id",'tmp_headers'=>['','生产厂家','出厂编号','使用状态','使用次数','备注'],'tmp_id'=>'off-sidebar','tmp_class'=>'table-tree table-all'])
    @include('template.sidebar-btn')
@endsection
