@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_menu'=>'standard','tmp_class'=>'btn-add','tmp_label'=>'增加'])
        @include('template.nav-btn',['tmp_menu'=>'standard','tmp_class'=>'btn-delete check-multiple','tmp_label'=>'删除'])
        @include('template.nav-btn',['tmp_menu'=>'standard','tmp_class'=>'btn-edit check-single','tmp_label'=>'修改'])
    </ul>
    @include('template.table',['tmp_menu'=>'standard','tmp_headers'=>['','标准','版本名称','检定部门','使用次数'],'tmp_class'=>'table-tree'])
@endsection

