@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_menu'=>'user','tmp_class'=>'btn-add','tmp_label'=>'增加'])
        @include('template.nav-btn',['tmp_menu'=>'user','tmp_class'=>'btn-delete check-multiple','tmp_label'=>'删除'])
        @include('template.nav-btn',['tmp_menu'=>'roles','tmp_class'=>'btn-edit check-multiple','tmp_label'=>'配置'])
        @include('template.nav-btn',['tmp_menu'=>'user','tmp_class'=>'btn-edit check-single','tmp_label'=>'修改'])
    </ul>
    @include('template.table',['tmp_menu'=>'user','tmp_headers'=>['','用户名','角色组']])
@endsection
