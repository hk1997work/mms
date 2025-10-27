@extends('layout.create')
@section('content_form')
    @include('template.table',['tmp_menu'=>'','tmp_headers'=>['','序号','岗位','器具名称','规格型号'],'tmp_id'=>'off-sidebar','tmp_class'=>'table-data','tmp_items'=>$certificates])
    <script>
        var settings = '{{$settings}}';
    </script>
    <script src="/admin/assets/js/pages/export.create.js"></script>
@endsection