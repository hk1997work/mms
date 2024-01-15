@extends('layout.show')
@section('content_title')
    <li><a class="active" data-toggle="tab" href="#number-tab" role="tab" id="number-btn">{{$tool->instrument}}详情</a></li>
@endsection
@section('content_form')
    <div role="tabpanel" class="tab-pane show active fade" id="number-tab" aria-labelledby="number-btn">
        <div class="col-12 ckp">
            <div class="btn-group mb-3">
                <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-3" role="tablist">
                    <li><a class="btn-add" data-pos="right" data-menu="factory" data-id='{{$tool->id}}' href="#">增加</a></li>
                    <li><a class="btn-delete check-multiple" data-pos="right" data-menu="number" href="#">删除</a></li>
                    <li><a class="btn-edit check-single" data-pos="right" data-menu="number" href="#">修改</a></li>
                    <li><a class="btn-show check-single" data-pos="up" data-menu="certificate" href="#">查看</a></li>
                </ul>
            </div>
            <table id="off-sidebar-table" data-menu="tool_show?id={{$tool->id}}" class="table table-hover mb-0 unsorted">
                <thead>
                <tr>
                    <th style="width:5%;">
                        <div class="styled-checkbox">
                            <input type="checkbox" name="check-all" class="check-all" id="check-all-show">
                            <label for="check-all-show"></label>
                        </div>
                    </th>
                    <th>生产厂家</th>
                    <th>出厂编号</th>
                    <th>使用状态</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="enter-message">
            <button class="btn btn-outline-secondary ripple sidebar-close">返 回</button>
        </div>
    </div>
@endsection
