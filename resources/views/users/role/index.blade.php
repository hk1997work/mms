@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_menu'=>'role','tmp_class'=>'btn-add','tmp_label'=>'增加'])
        @include('template.nav-btn',['tmp_menu'=>'role','tmp_class'=>'btn-delete check-multiple','tmp_label'=>'删除'])
        @include('template.nav-btn',['tmp_menu'=>'permissions','tmp_class'=>'btn-edit check-multiple','tmp_label'=>'配置'])
        @include('template.nav-btn',['tmp_menu'=>'role','tmp_class'=>'btn-edit check-single','tmp_label'=>'修改'])
    </ul>
    @include('template.table',['tmp_menu'=>'role','tmp_headers'=>['','角色','用户组','权限组','岗位组']])
@endsection