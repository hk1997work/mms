@extends('layout.show')
@section('content_title')
    @include('template.nav-tab',['name'=>'position','label'=>"{$position->name}量具配备",'active'=>true])
@endsection
@section('content_form')
    <ul class="nav">
        @include('template.nav-btn',['label'=>'修改','menu'=>'sn','class'=>'btn-edit check-single'])
        @include('template.nav-btn',['label'=>'查看','menu'=>'certificate','class'=>'btn-show check-single','pos'=>'up'])
        @include('template.nav-btn',['label'=>'上移','menu'=>'sn','class'=>'btn-move check-single','type'=>1])
        @include('template.nav-btn',['label'=>'下移','menu'=>'sn','class'=>'btn-move check-single','type'=>0])
    </ul>
    @include('template.table',['menu'=>"position_show?id=$position->id",'fields'=>['','序号','岗位','器具名称','管理状态','首次使用日期','证书数量'],'id'=>'off-sidebar','class'=>'table-all'])
    @include('template.btn')
@endsection
