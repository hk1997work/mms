@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_label'=>'查看','tmp_menu'=>'certificate','tmp_class'=>'btn-show check-single','tmp_pos'=>'up'])
        @include('template.nav-btn',['tmp_label'=>'验证','tmp_menu'=>'confirm','tmp_class'=>'btn-show check-single','tmp_pos'=>'up'])
    </ul>
    @include('template.table',['tmp_menu'=>'confirm','tmp_fields'=>['','序号','岗位','证书编号','器具名称','规格型号','出厂编号','检定日期','有效期','检定部门','校准依据','备注']])
@endsection
