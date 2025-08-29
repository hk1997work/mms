@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['label'=>'增加','menu'=>'parameter','class'=>'btn-add'])
        @include('template.nav-btn',['label'=>'删除','menu'=>'parameter','class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['label'=>'修改','menu'=>'parameter','class'=>'btn-edit check-single'])
        @include('template.nav-btn',['label'=>'上移','menu'=>'parameter','class'=>'btn-move check-single','type'=>1])
        @include('template.nav-btn',['label'=>'下移','menu'=>'parameter','class'=>'btn-move check-single','type'=>0])
    </ul>
    @include('template.table',['menu'=>'parameter','fields'=>['','分类','参数','数量'],'class'=>'table-tree'])
@endsection
