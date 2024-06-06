@extends('layout.show')
@section('content_title')
    <li><a class="active" data-toggle="tab" href="#position-tab" role="tab" id="position-btn">{{$position->name}}岗位量具配备情况</a></li>
@endsection
@section('content_form')
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane show active fade" id="position-tab" aria-labelledby="position-btn">
            <div class="col-12 ckp">
                <div class="btn-group mb-3">
                    <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-3" role="tablist">
                        <li><a class="btn-edit check-single" data-pos="right" data-menu="sn" href="#">修改</a></li>
                        <li><a class="btn-show check-single" data-pos="up" data-menu="certificate" href="#">查看</a></li>
                        <li><a class="btn-move check-single" data-menu="sn" data-type="1" href="#">上移</a></li>
                        <li><a class="btn-move check-single" data-menu="sn" data-type="0" href="#">下移</a></li>
                    </ul>
                </div>
                <table id="off-sidebar-table" data-menu="position_show?id={{$position->id}}" class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th style="width:5%;">
                            <div class="styled-checkbox">
                                <input type="checkbox" name="check-all" class="check-all" id="check-all-show">
                                <label for="check-all-show"></label>
                            </div>
                        </th>
                        <th>序号</th>
                        <th>岗位</th>
                        <th>器具名称</th>
                        <th>出厂编号</th>
                        <th>管理状态</th>
                        <th>首次使用日期</th>
                        <th>证书数量</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="enter-message">
                <button class="btn btn-outline-secondary ripple sidebar-close">返 回</button>
            </div>
        </div>
    </div>
@endsection
