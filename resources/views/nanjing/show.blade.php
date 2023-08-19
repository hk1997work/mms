@extends('layout.index')
@section('content_btn')
    <li class="nav-item"><a onclick="downItems()" class="nav-link">批量下载</a></li>
    <li class="nav-item"><a onclick="addItems()" class="nav-link">批量录入</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="modal" data-target="#filter_modal">屏蔽详情</a></li>
@endsection
@section('content_table')
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
                <tr @if($certificate->zsType=='检定结果通知书') class="bg-error" @endif>
                    <td>
                        <div class="d-xl-none">{{$certificate->jdrq}}</div>
                        @if($certificate->zsType!='检定结果通知书')
                            <div class="styled-checkbox mt-3">
                                <input type="checkbox" name="cb" id="{{$certificate->order}}">
                                <label for="{{$certificate->order}}"></label>
                                <input type="hidden" id="id{{$certificate->order}}" value="{{$certificate->id}}">
                                <input type="hidden" id="factory{{$certificate->order}}">
                            </div>
                        @endif
                    </td>
                    <td class="d-none d-xl-table-cell">{{$certificate->jdrq}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->zsbh}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->name}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->xhgg}}</td>
                    <td class="d-none d-xl-table-cell">{{isset($certificate->ccbh)?$certificate->ccbh=='/'?'':$certificate->ccbh:''}}{{isset($certificate->sbbh)?$certificate->sbbh=='/'?'':$certificate->sbbh:''}}</td>
                    <td class="d-xl-none">{{$certificate->name}}<br>{{$certificate->xhgg}}
                        <br>{{isset($certificate->ccbh)?$certificate->ccbh=='/'?'':$certificate->ccbh:''}}{{isset($certificate->sbbh)?$certificate->sbbh=='/'?'':$certificate->sbbh:''}}
                        <br>{{$certificate->zsbh}}
                    </td>
                    <td class="td-actions d-none d-sm-table-cell">
                        <a href="http://58.213.156.66/cmiims/static/angular/views/plugs/viewer.html?id={{$certificate->pdfDzqzPath}}" target="_blank"><i class="la la-eye edit"></i></a>
                        <a onclick='downItems("{{$certificate->order}}")'><i class="la la-download delete"></i></a>
                        <a onclick="modal_filter('{{$certificate->order}}')" data-toggle="modal" data-target="#filter"><i class="la la-eye-slash edit"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    <form action="/nj/download" method="post" id="form_down">
        {{csrf_field()}}
        <input type="hidden" name="idList" id="idList">
        <input type="hidden" name="data" id="data">
    </form>
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
                    <input type="text" name="remarks" id="remarks" class="form-control">
                </div>
                <div class="modal-footer">
                    <div id="btn_filter_ok"></div>
                    <button type="button" class="btn btn-secondary ripple" data-dismiss="modal">取 消</button>
                </div>
            </div>
        </div>
    </div>
    <!-- 结束 屏蔽模态框 -->
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
                            @foreach($nanjings as $nanjing)
                                <tr>
                                    <td>{{$nanjing->verification_date}}</td>
                                    <td>{{$nanjing->instrument}}<br>{{$nanjing->model}}<br>{{$nanjing->number}}<br>{{$nanjing->certificate_no}}<br>{{$nanjing->remark}}</td>
                                    <td class="td-actions">
                                        <a href="http://58.213.156.66/cmiims/static/angular/views/plugs/viewer.html?id={{$nanjing->pdf}}" target="_blank"><i class="la la-eye edit"></i></a>
                                        <a onclick="m_delete('nanjing',{{$nanjing->id}})" data-toggle="modal" data-target="#delete" data-dismiss="modal"><i class="la la-close delete"></i></a>
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
            function json() {
                return JSON.parse('{!! $json !!}'.replace('	', '').replace(' ', ''))
            }

            function load_factory(order, id, callback) {
                return new Promise((resolve, reject) => {
                    let parseData = $.base64.encode(JSON.stringify({"id": id}), "utf-8");
                    $.ajax({
                            type: "POST",
                            url: "https://lims.njsjly.com/cmiims/f/sys/webQuery/inquiryByIdInfo",
                            headers: {'Content-Type': 'application/json'},
                            data: parseData,
                            dataType: "text",
                            success: function (data) {
                                let jsonData = JSON.parse($.base64.decode(data, "utf-8"));
                                $('#factory' + order).val(jsonData.success ? jsonData.certificateInfo.zzcs : '读取失败')
                                if (typeof callback === 'function') {
                                    callback();
                                }
                                resolve();
                            }, error: function () {
                                $("#factory" + order).val("读取失败")
                                if (typeof callback === 'function') {
                                    callback();
                                }
                                resolve();
                            }
                        }
                    )
                })
            }

            async function addItems() {
                let arr = json()
                let idList = []
                let data = []
                let promises = [];
                if ($('input[name="cb"]:checked').length == 0) {
                    notifications('请选择数据!')
                    return
                }
                $("#preloader")[0].style.display = 'block';
                $('input[name="cb"]:checked').each(function () {
                    let order = $(this).attr('id');
                    if ($('#factory' + order).val() == '') {
                        let id = $("#id" + order).val()
                        promises.push(load_factory(order, id, function () {
                            arr[order].factory = $('#factory' + order).val()
                        }))
                    } else {
                        arr[order].factory = $('#factory' + order).val()
                    }
                    idList.push(arr[order].zsbh + "@@" + arr[order].pdfPath)
                    data.push(arr[order]);
                })
                await Promise.all(promises);
                try {
                    let result = await $.ajax({
                        url: "/nj/create",
                        type: "POST",
                        data: {
                            "_token": '{{csrf_token()}}',
                            "json": JSON.stringify(data),
                            "idList": $.base64.encode(escape(JSON.stringify(idList.join(",")))),
                            "data": $.base64.encode(escape(JSON.stringify(data)), "utf-8"),
                        }
                    });
                    $("#modal1").html(result);
                    $("#modal1").find(".btn-primary").attr('onclick', 'b_add("nj")')
                    $('#modal1').modal('show')
                    $(".has-danger").find('select').change(function () {
                        $(this).parents('.has-danger').find('.text-danger').remove()
                        $(this).parents('.has-danger').removeClass('has-danger')
                    });
                    $(".has-danger").find('input').change(function () {
                        $(this).parents('.has-danger').find('.text-danger').remove()
                        $(this).parents('.has-danger').removeClass('has-danger')
                    });
                } catch (error) {
                    if (error.status === 401) {
                        document.location.reload();
                    } else {
                        notifications('操作失败');
                    }
                }
                if ($("#preloader").is(':visible')) {
                    $("#preloader").fadeOut();
                }
            }

            function downItems(order) {
                let arr = json()
                let idList = []
                let data = []
                if (order) {
                    idList.push(arr[order].zsbh + "@@" + arr[order].pdfPath);
                    data.push(arr[order]);
                } else {
                    $('input[name="cb"]:checked').each(function () {
                        order = $(this).attr('id');
                        idList.push(arr[order].zsbh + "@@" + arr[order].pdfPath);
                        data.push(arr[order]);
                    })
                    if (idList.length == 0) {
                        notifications('请选择数据!')
                        return
                    }
                }
                $("#idList").val($.base64.encode(escape(JSON.stringify(idList.join(",")))))
                $("#data").val($.base64.encode(escape(JSON.stringify(data)), "utf-8"))
                $("#form_down").submit()
            }

            //屏蔽模态框
            function modal_filter(order) {
                let str = "<button class='btn btn-primary ripple' onclick=\"btn_filter('" + order + "')\" data-dismiss='modal'>确 定</button>"
                $("#btn_filter_ok").html(str);
            }

            //屏蔽操作
            function btn_filter(order) {
                let arr = json()
                $.ajax({
                        url: "/nj/filter",
                        type: "POST",
                        data: {
                            "_token": '{{csrf_token()}}',
                            "verification_date": arr[order].jdrq,
                            "certificate_no": arr[order].zsbh,
                            "instrument": arr[order].name,
                            "model": arr[order].xhgg,
                            "number": (arr[order].ccbh == "/" ? '' : arr[order].ccbh) + (arr[order].sbbh == "/" ? '' : [order].sbbh),
                            "remark": $("#remarks").val(),
                            "pdf": arr[order]['pdfDzqzPath'],
                        },
                        success: function (result) {
                            if (result == true) {
                                document.location.reload();
                            } else {
                                notifications('屏蔽失败');
                            }
                        }, error: function (xhr) {
                            if (xhr.status == 401) {
                                document.location.reload();
                            } else {
                                notifications('屏蔽失败');
                            }
                        }
                    }
                );
            }
        </script>
    @endif
@endsection

@push('page-js-after-1')
    <script>
        $(document).ready(function () {
            $('input[type="checkbox"]').on('change', function () {
                let order = $(this).attr('id')
                let id = $("#id" + order).val()
                if ($(this).is(':checked') && $('#factory' + order).val() == '') {
                    load_factory(order, id)
                }
            });
        });
    </script>
@endpush
