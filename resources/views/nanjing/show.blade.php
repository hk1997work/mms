@extends('layout.show')
@section('content_title')
    <li><a class="active" data-toggle="tab" href="#nanjing-tab" role="tab" id="nanjing-btn">屏蔽列表</a></li>
@endsection
@section('content_form')
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane show active fade" id="nanjing-tab" aria-labelledby="nanjing-btn">
            <div class="col-12 ckp">
                <div class="btn-group mb-3">
                    <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-3" role="tablist">
                        <li><a class="btn-delete check-multiple" data-menu="nanjing" data-pos="right" href="#">删除</a></li>
                        <li><a class="btn-open check-multiple" data-menu="nanjing" href="#">打开</a></li>
                        <li><a class="btn-download check-multiple" data-menu="nanjing" href="#">下载</a></li>
                    </ul>
                </div>
                <table id="off-sidebar-table" data-menu="nanjing_show" class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th style="width:5%;">
                            <div class="styled-checkbox">
                                <input type="checkbox" name="check-all" class="check-all" id="check-all-show">
                                <label for="check-all-show"></label>
                            </div>
                        </th>
                        <th>检定日期</th>
                        <th>证书编号</th>
                        <th>器具名称</th>
                        <th>规格型号</th>
                        <th>出厂编号</th>
                        <th>备注</th>
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
