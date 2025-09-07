@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_label'=>'增加','tmp_menu'=>'user','tmp_class'=>'btn-add'])
        @include('template.nav-btn',['tmp_label'=>'删除','tmp_menu'=>'user','tmp_class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['tmp_label'=>'配置','tmp_menu'=>'roles','tmp_class'=>'btn-edit check-multiple'])
        @include('template.nav-btn',['tmp_label'=>'修改','tmp_menu'=>'user','tmp_class'=>'btn-edit check-single'])
    </ul>
    @include('template.table',['tmp_menu'=>'user','tmp_fields'=>['','用户名','角色组']])
@endsection
