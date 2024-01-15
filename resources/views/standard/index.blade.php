@extends('layout.index')
@section('content_table')
    <div class="btn-group mb-3">
        <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-3" role="tablist">
            <li><a class="btn-add" data-pos="right" data-menu="standard" href="#">增加</a></li>
            <li><a class="btn-delete check-multiple" data-pos="right" data-menu="standard" href="#">删除</a></li>
            <li><a class="btn-edit check-single" data-pos="right" data-menu="standard" href="#">修改</a></li>
        </ul>
    </div>
    <table id="index-table" data-menu="standard" class="table table-hover mb-0 unsorted">
        <thead>
        <tr>
            <th style="width:5%;">
                <div class="styled-checkbox">
                    <input type="checkbox" name="check-all" class="check-all" id="check-all">
                    <label for="check-all"></label>
                </div>
            </th>
            <th>标准</th>
            <th>版本名称</th>
            <th>使用次数</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection

