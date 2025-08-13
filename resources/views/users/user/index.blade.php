@extends('layout.index')
@section('content_table')
    <ul class="nav" role="tablist">
        <li class="nav-item"><a class="nav-link btn-add" data-pos="right" data-menu="user" href="#">增加</a></li>
        <li class="nav-item"><a class="nav-link btn-delete check-multiple" data-pos="right" data-menu="user" href="#">删除</a></li>
        <li class="nav-item"><a class="nav-link btn-edit check-multiple" data-pos="right" data-menu="roles" href="#">配置</a></li>
        <li class="nav-item"><a class="nav-link btn-edit check-single" data-pos="right" data-menu="user" href="#">修改</a></li>
    </ul>
    <table id="index-table" data-menu="user" class="table table-hover">
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
