@extends('layout.index')
@section('content_btn')
    @include('template.nav-btn',['tmp_menu'=>'sample','tmp_class'=>'btn-show','tmp_label'=>'查看','tmp_pos'=>'up'])
    @include('template.nav-btn',['tmp_menu'=>'sample','tmp_class'=>'submit-download','tmp_label'=>'下载'])
    <form id="form-download" action="/sample" method="post">
        {{csrf_field()}}
        <input type="hidden" id="date-download" name="date">
    </form>
@endsection
@section('content_table')
    @include('template.table',['tmp_menu'=>"sample?id=$dates[0]",'tmp_headers'=>['序号','使用岗位','使用者','器具名称','规格型号','出厂编号','检测范围','检定合格证','备注'],'tmp_class'=>'table-all table-unselect'])
    <div id="timeline"></div>
@endsection
@push('page-js-after-1')
    <script>
        var dates = {!! $dates->toJson() !!};
    </script>
    <script src="/admin/assets/js/pages/sample.js"></script>
@endpush
