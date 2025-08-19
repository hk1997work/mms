@extends('layout.index')
@section('content_table')
    <ul class="nav">
        <li class="nav-item"><a class="nav-link btn-add" data-menu="parameter" href="#" data-pos="right">增加</a></li>
        <li class="nav-item"><a class="nav-link btn-delete check-multiple" data-menu="parameter" href="#" data-pos="right">删除</a></li>
        <li class="nav-item"><a class="nav-link btn-edit check-single" data-menu="parameter" href="#" data-pos="right">修改</a></li>
        <li class="nav-item"><a class="nav-link btn-move check-single" data-menu="parameter" href="#" data-type="1">上移</a></li>
        <li class="nav-item"><a class="nav-link btn-move check-single" data-menu="parameter" href="#" data-type="0">下移</a></li>
    </ul>
    <table id="index-table" data-menu="parameter" class="table table-hover table-list">
        <thead>
        <tr>
            <th></th>
            <th>分类</th>
            <th>参数</th>
            <th>数量</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection
