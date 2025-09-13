@extends('layout.show')
@section('content_title')
    @include('template.nav-tab',['tmp_name'=>'position','tmp_label'=>"{$position->name}量具配备",'tmp_active'=>true])
@endsection
@section('content_form')
    <ul class="nav">
        @include('template.nav-btn',['tmp_label'=>'修改','tmp_menu'=>'sn','tmp_class'=>'btn-edit check-single'])
        @include('template.nav-btn',['tmp_label'=>'查看','tmp_menu'=>'certificate','tmp_class'=>'btn-show check-single','tmp_pos'=>'up'])
        @include('template.nav-btn',['tmp_label'=>'上移','tmp_menu'=>'sn','tmp_class'=>'btn-move check-single','tmp_type'=>1])
        @include('template.nav-btn',['tmp_label'=>'下移','tmp_menu'=>'sn','tmp_class'=>'btn-move check-single','tmp_type'=>0])
    </ul>
    @include('template.table',['tmp_menu'=>"position_show?id=$position->id",'tmp_fields'=>['','序号','岗位','器具名称','管理状态','首次使用日期','证书数量'],'tmp_table_id'=>'off-sidebar','tmp_class'=>'table-all'])
    @include('template.sidebar-btn')
@endsection
