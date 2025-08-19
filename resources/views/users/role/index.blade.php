@extends('layout.index')
@section('content_table')
    <ul class="nav">
        <li class="nav-item"><a class="nav-link btn-add" data-menu="role" href="#" data-pos="right">增加</a></li>
        <li class="nav-item"><a class="nav-link btn-delete check-multiple" data-menu="role" href="#" data-pos="right">删除</a></li>
        <li class="nav-item"><a class="nav-link btn-edit check-multiple" data-menu="permissions" href="#" data-pos="right">配置</a></li>
        <li class="nav-item"><a class="nav-link btn-edit check-single" data-menu="role" href="#" data-pos="right">修改</a></li>
    </ul>
    <table id="index-table" data-menu="role" class="table table-hover">
        <thead>
        <tr>
            <th></th>
            <th>角色</th>
            <th>用户组</th>
            <th>权限组</th>
            <th>岗位组</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection