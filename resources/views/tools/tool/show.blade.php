@extends('layout.show')
@section('content_title')
    @include('template.nav-tab',['name'=>'number','label'=>"{$tool->instrument}详情",'active'=>true])
@endsection
@section('content_form')
    <ul class="nav">
        @include('template.nav-btn',['label'=>'增加','menu'=>'factory','class'=>'btn-add','id'=>$tool->id])
        @include('template.nav-btn',['label'=>'删除','menu'=>'number','class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['label'=>'修改','menu'=>'number','class'=>'btn-edit check-single'])
        @include('template.nav-btn',['label'=>'查看','menu'=>'number','class'=>'btn-show check-single','pos'=>'up'])
    </ul>
    @include('template.table',['menu'=>"tool_show?id=$tool->id",'fields'=>['','生产厂家','出厂编号','使用状态','使用次数','备注'],'id'=>'off-sidebar','class'=>'table-tree table-local'])
    @include('template.btn')
@endsection
