@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_label'=>'增加','tmp_menu'=>'role','tmp_class'=>'btn-add'])
        @include('template.nav-btn',['tmp_label'=>'删除','tmp_menu'=>'role','tmp_class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['tmp_label'=>'配置','tmp_menu'=>'permissions','tmp_class'=>'btn-edit check-multiple'])
        @include('template.nav-btn',['tmp_label'=>'修改','tmp_menu'=>'role','tmp_class'=>'btn-edit check-single'])
    </ul>
    @include('template.table',['tmp_menu'=>'role','tmp_fields'=>['','角色','用户组','权限组','岗位组']])
@endsection