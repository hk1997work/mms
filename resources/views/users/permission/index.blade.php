@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['label'=>'增加','menu'=>'permission','class'=>'btn-add'])
        @include('template.nav-btn',['label'=>'删除','menu'=>'permission','class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['label'=>'修改','menu'=>'permission','class'=>'btn-edit check-single'])
        @include('template.nav-btn',['label'=>'上移','menu'=>'permission','class'=>'btn-move check-single','type'=>1])
        @include('template.nav-btn',['label'=>'下移','menu'=>'permission','class'=>'btn-move check-single','type'=>0])
    </ul>
    @include('template.table',['menu'=>'permission','fields'=>['','权限','','','权限名称','角色组'],'class'=>'table-tree'])
@endsection
