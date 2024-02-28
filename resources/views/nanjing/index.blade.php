@extends('layout.index')
@section('content_btn')
    <li class="nav-item"><a class="nav-link btn-show" data-menu="nanjing" data-pos="left" href="#">屏蔽列表</a></li>
@endsection
@section('content_table')
    <div class="btn-group mb-3">
        <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-3" role="tablist">
            <li><a class="btn-add check-multiple" data-menu="nanjing" data-pos="left" data-id="checkbox" data-cb="load" href="#">录入</a></li>
            <li><a class="btn-edit check-multiple" data-menu="nanjing" data-pos="right" href="#">屏蔽</a></li>
            <li><a class="btn-open check-multiple" data-menu="nanjing" href="#">打开</a></li>
            <li><a class="btn-download check-multiple" data-menu="nanjing" href="#">下载</a></li>
        </ul>
    </div>
    <table id="index-table" data-menu="nanjing" class="table table-hover mb-0">
        <thead>
        <tr>
            <th style="width:5%;">
                <div class="styled-checkbox">
                    <input type="checkbox" name="check-all" class="check-all" id="check-all">
                    <label for="check-all"></label>
                </div>
            </th>
            <th>检定日期</th>
            <th>证书编号</th>
            <th>器具名称</th>
            <th>规格型号</th>
            <th>出厂编号</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection
@push('page-js-after-1')
    <link rel="stylesheet" href="/admin/assets/css/bootstrap-select/bootstrap-select.min.css">
    <script src="/admin/assets/vendors/js/datepicker/daterangepicker.js"></script>
    <script src="/admin/assets/vendors/js/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="/admin/assets/js/pages/nanjing.js"></script>
@endpush
