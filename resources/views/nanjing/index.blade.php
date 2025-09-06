@extends('layout.index')
@section('content_btn')
    <li class="nav-item"><a class="nav-link btn-show" data-menu="nanjing" href="#" data-pos="left">屏蔽列表</a></li>
@endsection
@section('content_table')
    <ul class="nav">
        <li class="nav-item"><a class="nav-link btn-add check-multiple" data-menu="nanjing" href="#" data-pos="left">录入</a></li>
        <li class="nav-item"><a class="nav-link btn-edit check-multiple" data-menu="nanjing" href="#" data-pos="right">屏蔽</a></li>
        <li class="nav-item"><a class="nav-link btn-open check-multiple" data-menu="nanjing" href="#">打开</a></li>
        <li class="nav-item"><a class="nav-link btn-download check-multiple" data-menu="nanjing" href="#">下载</a></li>
    </ul>
    <table id="index-table" data-menu="nanjing" class="table table-hover table-all">
        <thead>
        <tr>
            <th></th>
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
