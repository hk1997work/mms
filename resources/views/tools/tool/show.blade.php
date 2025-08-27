@extends('layout.show')
@section('content_title')
    <li class="nav-item">
        <button class="nav-link active" data-toggle="tab" data-target="#number-tab" id="number-btn">{{$tool->instrument}}详情</button>
    </li>
@endsection
@section('content_form')
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane show active" id="number-tab" aria-labelledby="number-btn">
            <div class="col-12 mt-3">
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link btn-add" data-menu="factory" href="#" data-pos="right" data-id="{{$tool->id}}">增加</a></li>
                    <li class="nav-item"><a class="nav-link btn-delete check-multiple" data-menu="number" href="#" data-pos="right">删除</a></li>
                    <li class="nav-item"><a class="nav-link btn-edit check-single" data-menu="number" href="#" data-pos="right">修改</a></li>
                    <li class="nav-item"><a class="nav-link btn-show check-single" data-menu="certificate" href="#" data-pos="up">查看</a></li>
                </ul>
                <table id="off-sidebar-table" data-menu="tool_show?id={{$tool->id}}" class="table table-hover table-tree">
                    <thead>
                    <tr>
                        <th></th>
                        <th>生产厂家</th>
                        <th>出厂编号</th>
                        <th>使用状态</th>
                        <th>使用次数</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="position-fixed bottom-0 end-0 p-3">
                <button class="btn btn-outline-secondary sidebar-close sidebar-url">取 消</button>
            </div>
        </div>
    </div>
@endsection
