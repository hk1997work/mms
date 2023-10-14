@extends('layout.index')
@section('content_btn')
    <li class="nav-item"><a onclick="btn_open()" class="nav-link">批量查看</a></li>
    <li class="nav-item"><a onclick="btn_download()" class="nav-link">批量下载</a></li>
    <li class="nav-item"><a onclick="btn_add()" class="nav-link">批量录入</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="modal" data-target="#filter_modal">屏蔽详情</a></li>
@endsection
@section('content_table')
    <form action="" onsubmit="return false;" id="form_jiangsu">
        {{csrf_field()}}
        <table id="export-table" class="table table-hover mb-0">
            <thead>
            <tr>
                <th class="d-none d-xl-table-cell">
                    <div class="styled-checkbox mt-3">
                        <input type="checkbox" name="check-all" id="check-all">
                        <label for="check-all"></label>
                    </div>
                </th>
                <th>检定日期</th>
                <th class="d-none d-xl-table-cell">证书编号</th>
                <th class="d-none d-xl-table-cell">器具名称</th>
                <th class="d-none d-xl-table-cell">规格型号</th>
                <th class="d-none d-xl-table-cell">出厂编号</th>
                <th class="d-xl-none">证书信息</th>
                <th class="d-none d-sm-table-cell">操作</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($certificates))
                @foreach($certificates as $certificate)
                    <tr>
                        <td>
                            <div class="d-xl-none">{{$certificate->zsJdrq}}</div>
                            <div class="styled-checkbox mt-3">
                                <input type="checkbox" name="cb" id="{{$certificate->zsId}}">
                                <label for="{{$certificate->zsId}}"></label>
                            </div>
                        </td>
                        <td class="d-none d-xl-table-cell">{{$certificate->zsJdrq}}</td>
                        <td class="d-none d-xl-table-cell">{{$certificate->zsZsh}}</td>
                        <td class="d-none d-xl-table-cell">{{$certificate->zsQjmc}}</td>
                        <td class="d-none d-xl-table-cell">{{$certificate->zsXhgg}}</td>
                        <td class="d-none d-xl-table-cell">{{isset($certificate->zsCcbh)?$certificate->zsCcbh=='/'?'':$certificate->zsCcbh:''}}{{isset($certificate->zsSbbh)?$certificate->zsSbbh=='/'?'':$certificate->zsSbbh:''}}</td>
                        <td class="d-xl-none">{{$certificate->zsQjmc}}<br>{{$certificate->zsXhgg}}
                            <br>{{isset($certificate->zsCcbh)?$certificate->zsCcbh=='/'?'':$certificate->zsCcbh:''}}{{isset($certificate->zsSbbh)?$certificate->zsSbbh=='/'?'':$certificate->zsSbbh:''}}
                            <br>{{$certificate->zsZsh}}
                        </td>
                        <td class="td-actions d-none d-sm-table-cell">
                            <a href="/jiangsu/1?str={{str_replace('#','@',json_encode($certificate))}}" target="_blank"><i class="la la-eye edit"></i></a>
                            <a href="/jiangsu/0?str={{str_replace('#','@',json_encode($certificate))}}"><i class="la la-download delete"></i></a>
                            <a onclick="m_edit('jiangsu',{{$certificate->zsId}},'{{str_replace('#','@',json_encode($certificate))}}')"><i class="la la-eye-slash edit"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </form>
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
                    <div class="table-responsive" style="max-height:500px;">
                        <table id="filter-table" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>检定日期</th>
                                <th>证书信息</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($jiangsu as $n)
                                <tr>
                                    <td>{{$n->verification_date}}</td>
                                    <td>{{$n->instrument}}<br>{{$n->model}}<br>{{$n->number}}<br>{{$n->certificate_no}}<br>{{$n->remark}}</td>
                                    <td class="td-actions">
                                        <a href="/jiangsu/1?str={{str_replace('#','@',$n->pdf)}}" target="_blank"><i class="la la-eye edit"></i></a>
                                        <a onclick="m_delete('jiangsu',{{$n->id}})" data-toggle="modal" data-target="#delete" data-dismiss="modal"><i class="la la-close delete"></i></a>
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
    @if(isset($certificates))
        <script>
            function btn_add() {
                let check = []
                if ($("#export-table input[type='checkbox'][name='cb']:checked").length > 10) {
                    notifications('最多选择10条数据,当前数据' + $("#export-table input[type='checkbox'][name='cb']:checked").length + '条')
                } else if ($("#export-table input[type='checkbox'][name='cb']:checked").length > 0) {
                    $("#export-table input[type='checkbox'][name='cb']:checked").each(function () {
                        check.push($(this).attr('id'))
                    });
                    m_add('jiangsu', check.join(','), 1)
                } else {
                    notifications('请选择数据');
                }
            }

            function btn_open() {
                if ($("#export-table input[type='checkbox'][name='cb']:checked").length > 0) {
                    $("#export-table input[type='checkbox'][name='cb']:checked").each(function () {
                        window.open($(this).closest('tr').find('.td-actions a:first').attr('href'), '_blank')
                    });
                } else {
                    notifications('请选择数据');
                }
            }

            function btn_download() {
                if ($("#export-table input[type='checkbox'][name='cb']:checked").length > 0) {
                    $("#export-table input[type='checkbox'][name='cb']:checked").each(function () {
                        window.open($(this).closest('tr').find('.td-actions a:eq(1)').attr('href'), '_blank')
                    });
                } else {
                    notifications('请选择数据');
                }
            }
        </script>
    @endif
@endsection
