@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_label'=>'增加','tmp_menu'=>'parameter','tmp_class'=>'btn-add'])
        @include('template.nav-btn',['tmp_label'=>'删除','tmp_menu'=>'parameter','tmp_class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['tmp_label'=>'修改','tmp_menu'=>'parameter','tmp_class'=>'btn-edit check-single'])
        @include('template.nav-btn',['tmp_label'=>'上移','tmp_menu'=>'parameter','tmp_class'=>'btn-move check-single','tmp_type'=>1])
        @include('template.nav-btn',['tmp_label'=>'下移','tmp_menu'=>'parameter','tmp_class'=>'btn-move check-single','tmp_type'=>0])
    </ul>
    @include('template.table',['tmp_menu'=>'parameter','tmp_fields'=>['','分类','参数','数量'],'tmp_class'=>'table-tree'])
@endsection
