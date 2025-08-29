@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav',['label'=>'增加','menu'=>'standard','class'=>'btn-add'])
        @include('template.nav',['label'=>'删除','menu'=>'standard','class'=>'btn-delete check-multiple'])
        @include('template.nav',['label'=>'修改','menu'=>'standard','class'=>'btn-edit check-single'])
    </ul>
    @include('template.table',['menu'=>'standard','fields'=>['标准','版本名称','使用次数'],'class'=>'table-tree'])
@endsection

