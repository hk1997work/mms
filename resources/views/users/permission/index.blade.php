@extends('layout.index')
@section('content_table')
    <ul class="nav">
        <li class="nav-item"><a class="nav-link btn-add" data-menu="permission" href="#" data-pos="right">增加</a></li>
        <li class="nav-item"><a class="nav-link btn-delete check-multiple" data-menu="permission" href="#" data-pos="right">删除</a></li>
        <li class="nav-item"><a class="nav-link btn-edit check-single" data-menu="permission" href="#" data-pos="right">修改</a></li>
        <li class="nav-item"><a class="nav-link btn-move check-single" data-menu="permission" href="#" data-type="1">上移</a></li>
        <li class="nav-item"><a class="nav-link btn-move check-single" data-menu="permission" href="#" data-type="0">下移</a></li>
    </ul>
    <table id="index-table" data-menu="permission" class="table table-hover table-list">
        <thead>
        <tr>
            <th></th>
            <th>权限</th>
            <th></th>
            <th></th>
            <th>权限名称</th>
            <th>角色组</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection
