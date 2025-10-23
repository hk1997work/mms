@extends('layout.index')
@section('content_btn')
    @include('template.dropdown',['tmp_label'=>$type->name,'tmp_items'=>$types,'tmp_href'=>'id','tmp_field'=>'name'])
@endsection
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_menu'=>'tool','tmp_id'=>$type->id,'tmp_class'=>'btn-add','tmp_label'=>'增加'])
        @include('template.nav-btn',['tmp_menu'=>'tool','tmp_class'=>'btn-delete check-multiple','tmp_label'=>'删除'])
        @include('template.nav-btn',['tmp_menu'=>'tool','tmp_class'=>'btn-edit check-single','tmp_label'=>'修改'])
        @include('template.nav-btn',['tmp_menu'=>'tool','tmp_class'=>'btn-show check-single','tmp_label'=>'查看','tmp_pos'=>'left'])
    </ul>
    @include('template.table',['tmp_menu'=>"tool?id=$type->id",'tmp_headers'=>['','器具名称','规格型号','测量范围','精确度','检定周期','ABC','在用','待检','封存','损坏','报废','合计','易损','检定要求']])
@endsection
