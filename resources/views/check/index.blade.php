@extends('layout.main')

@section('content')
    <!-- 开始 行 -->
    <div class="row flex-row">
        <div class="col-xl-12">
            <!-- 开始 列表 -->
            <div class="widget has-shadow">
                <div class="widget-header bordered d-flex align-items-center">
                    <h2>数据验证</h2>
                    <li class="nav-item"><a class="nav-link" data-toggle="modal" data-target="#filter_modal">屏蔽详情</a></li>
                </div>
                <div class="widget-body">
                    <div class="table-responsive">
                        <table id="index-table" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>描述</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($checks as $check)
                                <tr>
                                    <td>{{$check->order}}</td>
                                    <td>{{$check->event}}</td>
                                    <td class="td-actions">
                                        <a onclick="modal_filter('{{$check->order}}','{{$check->event}}')" data-toggle="modal" data-target="#filter"><i class="la la-eye-slash delete"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- 结束 列表 -->
        </div>
    </div>
    <!-- 结束 行 -->
    <!-- 开始 屏蔽模态框 -->
    <div id="filter" class="modal fade" data-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">确认屏蔽?</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label class="form-control-label">备注</label>
                    <input type="text" name="remark" id="remark" class="form-control">
                </div>
                <div class="modal-footer">
                    <div id="btn_filter_ok"></div>
                    <button type="button" class="btn btn-secondary ripple" data-dismiss="modal">取 消</button>
                </div>
            </div>
        </div>
    </div>
    <!-- 结束 屏蔽模态框 -->
    <!-- 开始 删除模态框 -->
    <div id="delete" class="modal fade" data-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">确认删除?</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <div id="btn_delete_ok"></div>
                    <button type="button" class="btn btn-secondary ripple" data-dismiss="modal">取 消</button>
                </div>
            </div>
        </div>
    </div>
    <!-- 结束 删除模态框 -->
    <div id="filter_modal" class="modal fade" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">屏蔽列表</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="index-table" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>描述</th>
                                <th>备注</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($filters as $filter)
                                <tr>
                                    <td>{{$filter->order}}</td>
                                    <td>{{$filter->event}}</td>
                                    <td>{{$filter->remark}}</td>
                                    <td class="td-actions d-none d-sm-table-cell">
                                        <a onclick="modal_delete({{$filter->id}})" data-toggle="modal" data-target="#delete" data-dismiss="modal"><i class="la la-close delete"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary ripple" data-dismiss="modal">返 回</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        //屏蔽模态框
        function modal_filter(order, event) {
            var str = "<button class='btn btn-primary ripple' onclick=\"btn_filter('" + order + "','" + event + "')\" data-dismiss='modal'>确 定</button>"
            $("#btn_filter_ok").html(str);
        }

        //屏蔽操作
        function btn_filter(order, event) {
            $.post("/check", {
                    "_method": "post",
                    "data": {"order": order, "event": event, "remark": $("#remark").val()},
                    "_token": '{{csrf_token()}}'
                },
                function (data) {
                    if (data == true) {
                        document.location.reload();
                    } else {
                        notifications('屏蔽失败');
                    }
                });
        }

        //删除模态框
        function modal_delete(id) {
            var str = '<button class="btn btn-primary ripple" onclick="btn_delete(' + id + ')" data-dismiss="modal">确 定</button>';
            $("#btn_delete_ok").html(str);
        }

        //删除操作
        function btn_delete(id) {
            $.post("/check/" + id, {"_method": "delete", "_token": '{{csrf_token()}}'},
                function (data) {
                    if (data == true) {
                        document.location.reload();
                    } else {
                        notifications('删除失败');
                    }
                });
        }
    </script>
@endsection

@push('page-css')
    <link rel="stylesheet" href="/admin/assets/css/datatables/datatables.min.css">
@endpush
@push('page-js-before')
    <script src="/admin/assets/vendors/js/datatables/datatables.min.js"></script>
    <script src="/admin/assets/js/components/tables/tables.js"></script>
@endpush
