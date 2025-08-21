@extends('layout.index')
@section('content_btn')
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{$type->name}}<i class="ion-android-arrow-dropdown"></i></a>
        <ul class="dropdown-menu dropdown-menu-end">
            @foreach($types as $value)
                <li><a class="dropdown-item" href="?id={{$value->id}}">{{$value->name}}</a></li>
            @endforeach
        </ul>
    </li>
@endsection
@section('content_table')
    <ul class="nav">
        <li class="nav-item"><a class="nav-link btn-add" data-menu="tool" href="#" data-pos="right" data-id="{{$type->id}}">增加</a></li>
        <li class="nav-item"><a class="nav-link btn-delete check-multiple" data-menu="tool" href="#" data-pos="right">删除</a></li>
        <li class="nav-item"><a class="nav-link btn-edit check-single" data-menu="tool" href="#" data-pos="right">修改</a></li>
        <li class="nav-item"><a class="nav-link btn-show check-single" data-menu="tool" href="#" data-pos="left">查看</a></li>
    </ul>
    <table id="index-table" data-menu="tool?id={{$type->id}}" class="table table-hover">
        <thead>
        <tr>
            <th></th>
            <th>器具名称</th>
            <th>规格型号</th>
            <th>测量范围</th>
            <th>精确度</th>
            <th>检定周期</th>
            <th>ABC</th>
            <th>在用</th>
            <th>待检</th>
            <th>封存</th>
            <th>损坏</th>
            <th>报废</th>
            <th>合计</th>
            <th>易损</th>
            <th>检定要求</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection
