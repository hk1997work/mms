@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_label'=>'增加','tmp_menu'=>'standard','tmp_class'=>'btn-add'])
        @include('template.nav-btn',['tmp_label'=>'删除','tmp_menu'=>'standard','tmp_class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['tmp_label'=>'修改','tmp_menu'=>'standard','tmp_class'=>'btn-edit check-single'])
    </ul>
    @include('template.table',['tmp_menu'=>'standard','tmp_fields'=>['','标准','版本名称','检定部门','使用次数'],'tmp_class'=>'table-tree'])
@endsection

