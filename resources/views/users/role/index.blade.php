@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['label'=>'增加','menu'=>'role','class'=>'btn-add'])
        @include('template.nav-btn',['label'=>'删除','menu'=>'role','class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['label'=>'配置','menu'=>'permissions','class'=>'btn-edit check-multiple'])
        @include('template.nav-btn',['label'=>'修改','menu'=>'role','class'=>'btn-edit check-single'])
    </ul>
    @include('template.table',['menu'=>'role','fields'=>['','角色','用户组','权限组','岗位组']])
@endsection