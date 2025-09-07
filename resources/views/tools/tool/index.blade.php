@extends('layout.index')
@section('content_btn')
    @include('template.dropdown',['tmp_label'=>$type->name,'tmp_items'=>$types,'tmp_id'=>'id','tmp_field'=>'name'])
@endsection
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_label'=>'增加','tmp_menu'=>'tool','tmp_class'=>'btn-add','tmp_id'=>$type->id])
        @include('template.nav-btn',['tmp_label'=>'删除','tmp_menu'=>'tool','tmp_class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['tmp_label'=>'修改','tmp_menu'=>'tool','tmp_class'=>'btn-edit check-single'])
        @include('template.nav-btn',['tmp_label'=>'查看','tmp_menu'=>'tool','tmp_class'=>'btn-show check-single','tmp_pos'=>'left'])
    </ul>
    @include('template.table',['tmp_menu'=>'tool?id='.$type->id,'tmp_fields'=>['','器具名称','规格型号','测量范围','精确度','检定周期','ABC','在用','待检','封存','损坏','报废','合计','易损','检定要求']])
@endsection
