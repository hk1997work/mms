@extends('layout.index')
@section('content_btn')
    <li class="nav-item"><a onclick="btn_modal()" class="nav-link">获取文本</a></li>
    <li class="nav-item"><a onclick="btn_open()" class="nav-link">批量查看</a></li>
    <li class="nav-item"><a onclick="btn_download()" class="nav-link">批量下载</a></li>
@endsection
@section('content_table')
    <form action="" onsubmit="return false;" id="form_setting">
        {{csrf_field()}}
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
            <input type="text" name="input_export" id="input_export" class="form-control" disabled>
            <div class="styled-checkbox mt-3">
                <input type="checkbox" name="check_export" id="check_export">
                <label for="check_export">导出格式</label>
            </div>
            <div>参数：@foreach(collect($certificates->first())->keys() as $value) {{'{'.$value.'}'}} @endforeach</div>
        </div>
        <table id="export-table" class="table table-hover mb-0">
            <thead>
            <tr>
                <th class="d-none d-xl-table-cell"></th>
                <th>序号</th>
                <th class="d-none d-xl-table-cell">岗位</th>
                <th class="d-none d-xl-table-cell">器具名称</th>
                <th class="d-none d-xl-table-cell">规格型号</th>
                <th class="d-none d-xl-table-cell">出厂编号</th>
                <th class="d-none d-xl-table-cell">检定日期</th>
                <th class="d-none d-xl-table-cell">有效期</th>
                <th class="d-none d-xl-table-cell">检定部门</th>
                <th class="d-none d-xl-table-cell">备注</th>
                <th class="d-xl-none">标签</th>
            </tr>
            </thead>
            <tbody>
            @foreach($certificates as $certificate)
                <tr>
                    <td>
                        <div class="d-xl-none">{{$certificate->order}}<br>{{$certificate->position}}<br>{{$certificate->instrument}}<br>{{$certificate->model}}</div>
                        <div class="styled-checkbox mt-3">
                            <input type="checkbox" name="cb[{{$certificate->id}}]" id="cb[{{$certificate->id}}]">
                            <label for="cb[{{$certificate->id}}]"></label>
                            <a style="display: none;"
                               href="/download/pdf/certificate/{{$certificate->id.'/'.urlencode($certificate->order.'--'.$certificate->instrument.'--'.$certificate->number.'--【'.date('Y年m月d日',strtotime($certificate->verification_date)).'-'.date('Y年m月d日',strtotime($certificate->validity_date)).'】')}}"></a>
                        </div>
                    </td>
                    <td class="d-none d-xl-table-cell">{{$certificate->order}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->position}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->instrument}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->model}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->number}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->verification_date}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->validity_date}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->department}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->remark}}</td>
                    <td class="d-xl-none">{{$certificate->number}}<br>{{$certificate->verification_date}}<br>{{$certificate->validity_date}}<br>{{$certificate->department}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </form>
@endsection
@push('page-js-after-1')
    <script>
        function btn_modal() {
            if ($("#export-table input[type='checkbox']:checked").length > 0) {
                $.ajax({
                    url: "/supervision",
                    type: "POST",
                    data: new FormData($("#form_setting")[0]),
                    processData: false,  // 不处理数据
                    contentType: false,   // 不设置内容类型
                    success: function (data) {
                        if (data == false) {
                            notifications('请选择数据');
                        } else {
                            let str = `
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">检查情况</h4>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span aria-hidden="true">×</span>
                                        <span class="sr-only">close</span>
                                    </button>
                                </div>
                                <div class="modal-body"><div class="text-primary">` + data + `</div></div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary ripple" data-dismiss="modal">返 回</button>
                                </div>
                            </div>
                        </div>
                        `;
                            $('#modal1').html(str);
                            $('#modal1').modal('show')
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status == 401) {
                            document.location.reload();
                        } else {
                            notifications('提交失败');
                        }
                    }
                });
            } else {
                notifications('请选择数据!')
            }
        }

        function btn_open() {
            if ($("#export-table input[type='checkbox']:checked").length > 0) {
                $("#export-table input[type='checkbox']:checked").each(function () {
                    window.open('/storage/certificate/' + $(this).attr('id').replace('cb[', '').replace(']', '') + '.pdf', '_blank');
                });
            } else {
                notifications('请选择数据');
            }
        }

        function btn_download() {
            if ($("#export-table input[type='checkbox']:checked").length > 0) {
                $("#export-table input[type='checkbox']:checked").each(function () {
                    window.open($(this).siblings("a:first").attr('href'), '_blank')
                });
            } else {
                notifications('请选择数据');
            }
        }

        $('#check_export').change(function () {
            if ($('#check_export').is(':checked')) {
                $("#input_export").prop("disabled", false);
            } else {
                $('#input_export').prop("disabled", true);
            }
        })
    </script>
@endpush
