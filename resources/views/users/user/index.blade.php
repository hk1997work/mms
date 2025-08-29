@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav',['label'=>'增加','menu'=>'user','class'=>'btn-add'])
        @include('template.nav',['label'=>'删除','menu'=>'user','class'=>'btn-delete check-multiple'])
        @include('template.nav',['label'=>'配置','menu'=>'roles','class'=>'btn-edit check-multiple'])
        @include('template.nav',['label'=>'修改','menu'=>'user','class'=>'btn-edit check-single'])
    </ul>
    @include('template.table',['menu'=>'user','fields'=>['用户名','角色组']])
@endsection
