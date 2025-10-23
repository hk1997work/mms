@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_menu'=>'permission','tmp_class'=>'btn-add','tmp_label'=>'增加'])
        @include('template.nav-btn',['tmp_menu'=>'permission','tmp_class'=>'btn-delete check-multiple','tmp_label'=>'删除'])
        @include('template.nav-btn',['tmp_menu'=>'permission','tmp_class'=>'btn-edit check-single','tmp_label'=>'修改'])
        @include('template.nav-btn',['tmp_menu'=>'permission','tmp_class'=>'btn-move check-single','tmp_label'=>'上移','tmp_type'=>1])
        @include('template.nav-btn',['tmp_menu'=>'permission','tmp_class'=>'btn-move check-single','tmp_label'=>'下移','tmp_type'=>0])
    </ul>
    @include('template.table',['tmp_menu'=>'permission','tmp_headers'=>['','权限','','','权限名称','角色组'],'tmp_class'=>'table-tree'])
@endsection
