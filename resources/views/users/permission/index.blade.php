@extends('layout.index')
@section('content_table')
    <div class="btn-group mb-3">
        <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-3" role="tablist">
            <li><a class="btn-add" data-pos="right" data-menu="permission" href="#">增加</a></li>
            <li><a class="btn-delete check-multiple" data-pos="right" data-menu="permission" href="#">删除</a></li>
            <li><a class="btn-edit check-single" data-pos="right" data-menu="permission" href="#">修改</a></li>
            <li><a class="btn-move check-single" data-menu="permission" data-type="1" href="#">上移</a></li>
            <li><a class="btn-move check-single" data-menu="permission" data-type="0" href="#">下移</a></li>
        </ul>
    </div>
    <table id="index-table" data-menu="permission" class="table table-hover mb-0 unsorted">
        <thead>
        <tr>
            <th style="width:5%;">
                <div class="styled-checkbox">
                    <input type="checkbox" name="check-all" class="check-all" id="check-all">
                    <label for="check-all"></label>
                </div>
            </th>
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
