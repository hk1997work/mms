@extends('layout.index')
@section('content_btn')
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown">{{$type->name}}<i class="ion-android-arrow-dropdown"></i></a>
        <div class="dropdown-menu">
            @foreach($types as $value)
                <a class="dropdown-item" href="?id={{$value->id}}">{{$value->name}}</a>
            @endforeach
        </div>
    </li>
@endsection
@section('content_table')
    <div class="btn-group mb-3">
        <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-3" role="tablist">
            <li><a class="btn-add" data-pos="right" data-menu="tool" data-id='{{$type->id}}' href="#">增加</a></li>
            <li><a class="btn-delete check-multiple" data-pos="right" data-menu="tool" href="#">删除</a></li>
            <li><a class="btn-edit check-single" data-pos="right" data-menu="tool" href="#">修改</a></li>
            <li><a class="btn-show check-single" data-pos="left" data-menu="tool" href="#">查看</a></li>
        </ul>
    </div>
    <table id="index-table" data-menu="tool?id={{$type->id}}" class="table table-hover mb-0">
        <thead>
        <tr>
            <th style="width:5%;">
                <div class="styled-checkbox">
                    <input type="checkbox" name="check-all" class="check-all" id="check-all">
                    <label for="check-all"></label>
                </div>
            </th>
            <th>器具名称</th>
            <th>规格型号</th>
            <th>测量范围</th>
            <th>精确度</th>
            <th>检定周期</th>
            <th>ABC</th>
            <th>在用</th>
            <th>备用</th>
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
