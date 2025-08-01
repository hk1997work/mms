@extends('layout.index')
@section('content_table')
    <div class="btn-group mb-2">
        <ul class="button-nav nav nav-tabs" role="tablist">
            <li><a class="btn-add" data-pos="right" data-menu="user" href="#">增加</a></li>
            <li><a class="btn-delete check-multiple" data-pos="right" data-menu="user" href="#">删除</a></li>
            <li><a class="btn-edit check-multiple" data-pos="right" data-menu="roles" href="#">配置</a></li>
            <li><a class="btn-edit check-single" data-pos="right" data-menu="user" href="#">修改</a></li>
        </ul>
    </div>
    <table id="index-table" data-menu="user" class="table table-hover mb-0">
        <thead>
        <tr>
            <th></th>
            <th>用户名</th>
            <th>角色组</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection
