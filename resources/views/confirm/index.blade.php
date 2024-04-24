@extends('layout.index')
@section('content_table')
    <div class="btn-group mb-3">
        <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-3" role="tablist">
            <li><a class="btn-show check-single" data-menu="certificate" data-pos="up" href="#">查看</a></li>
            <li><a class="btn-show check-single" data-menu="confirm" data-pos="left" href="#">验证</a></li>
        </ul>
    </div>
    <table id="index-table" data-menu="confirm" class="table table-hover mb-0">
        <thead>
        <tr>
            <th style="width:5%;">
                <div class="styled-checkbox">
                    <input type="checkbox" name="check-all" class="check-all" id="check-all">
                    <label for="check-all"></label>
                </div>
            </th>
            <th>序号</th>
            <th>岗位</th>
            <th>证书编号</th>
            <th>器具名称</th>
            <th>规格型号</th>
            <th>出厂编号</th>
            <th>检定日期</th>
            <th>有效期</th>
            <th>检定部门</th>
            <th>校准依据</th>
            <th>备注</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection
