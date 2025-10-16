@extends('layout.edit')
@section('content_title')
    @include('template.nav-tab',['tmp_name'=>'permission','tmp_label'=>'权限配置','tmp_active'=>true])
    @include('template.nav-tab',['tmp_name'=>'position','tmp_label'=>'岗位配置','tmp_active'=>false])
@endsection
@section('content_form')
    <div class="tab-content">
        @include('template.checklist',['tmp_items'=>$permissions,'tmp_name'=>'permission','tmp_field'=>'description','tmp_checked'=>$myPermissions,'tmp_show'=>true])
        @include('template.checklist',['tmp_items'=>$positions,'tmp_name'=>'position','tmp_field'=>'name','tmp_checked'=>$myPositions])
    </div>
    <script src="/admin/assets/js/pages/permission.js"></script>
@endsection
