@extends('layout.index')
@section('content_table')
    <ul class="nav">
        <li class="nav-item"><a class="nav-link btn-add" data-menu="standard" href="#" data-pos="right">增加</a></li>
        <li class="nav-item"><a class="nav-link btn-delete check-multiple" data-menu="standard" href="#" data-pos="right">删除</a></li>
        <li class="nav-item"><a class="nav-link btn-edit check-single" data-menu="standard" href="#" data-pos="right">修改</a></li>
    </ul>
    <table id="index-table" data-menu="standard" class="table table-hover table-tree">
        <thead>
        <tr>
            <th></th>
            <th>标准</th>
            <th>版本名称</th>
            <th>使用次数</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection

