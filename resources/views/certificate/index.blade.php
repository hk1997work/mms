@extends('layout.index')

@section('content_btn')
    @if($menu=='active')
        <li class="nav-item"><a onclick="m_add('certificate',{{$position->id}})" class="nav-link">增加证书</a></li>
    @endif
    @if($menu=='active'||$menu=='invalid')
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown">
                {{$position->level==2?$position->name:$types->find($position->pid)->name1}}
                <i class="ion-android-arrow-dropdown"></i>
            </a>
            <div class="dropdown-menu">
                @foreach($types->where('level',2) as $t)
                    <a class="dropdown-item" href="?position_id={{$t->id}}">{{$t->name1}}</a>
                @endforeach
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown">
                {{$position->level==2?'全部':$position->name}}
                <i class="ion-android-arrow-dropdown"></i>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="?position_id={{$position->level==2?$position->id:$position->pid}}">全部</a>
                @foreach($types->where('pid',$position->level==2?$position->id:$position->pid) as $t)
                    <a class="dropdown-item" href="?position_id={{$t->id}}">{{$t->name1}}</a>
                @endforeach
            </div>
        </li>
    @endif
@endsection

@section('content_table')
    <table id="sorting-table" class="table table-hover mb-0">
        <thead>
        <tr>
            <th class="d-none d-xl-table-cell">序号</th>
            <th class="d-none d-xl-table-cell">证书编号</th>
            <th class="d-none d-xl-table-cell">岗位</th>
            <th class="d-none d-xl-table-cell">器具名称</th>
            <th class="d-none d-xl-table-cell">规格型号</th>
            <th class="d-none d-xl-table-cell">出厂编号</th>
            <th class="d-none d-xl-table-cell">检定日期</th>
            <th class="d-none d-xl-table-cell">有效期</th>
            <th class="d-none d-xl-table-cell">检定部门</th>
            <th class="d-none d-xl-table-cell">备注</th>
            <th class="d-xl-none">名称</th>
            <th class="d-xl-none">标签</th>
            <th class="d-none d-sm-table-cell">操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($certificates as $certificate)
            <tr @if($certificate->valid == 1 && $certificate->validity_date < date("Y-m-d")) class="bg-error" @endif>
                <td class="d-none d-xl-table-cell">{{$certificate->order}}</td>
                <td class="d-none d-xl-table-cell">{{$certificate->certificate_no}}</td>
                <td class="d-none d-xl-table-cell">{{$certificate->position}}</td>
                <td class="d-none d-xl-table-cell">{{$certificate->instrument}}</td>
                <td class="d-none d-xl-table-cell">{{$certificate->model}}</td>
                <td class="d-none d-xl-table-cell">{{$certificate->number}}</td>
                <td class="d-none d-xl-table-cell">{{$certificate->verification_date}}</td>
                <td class="d-none d-xl-table-cell">{{$certificate->validity_date}}</td>
                <td class="d-none d-xl-table-cell">{{$certificate->department}}</td>
                <td class="d-none d-xl-table-cell">{{$certificate->remark}}</td>
                <td class="d-xl-none">{{$certificate->order}}<br>{{$certificate->position}}<br>{{$certificate->instrument}}<br>{{$certificate->model}}</td>
                <td class="d-xl-none">{{$certificate->number}}<br>{{$certificate->verification_date}}<br>{{$certificate->validity_date}}
                    <br>{{$certificate->department}}</td>
                <td class="td-actions d-none d-sm-table-cell">
                    @if($menu=='active'&&($certificate->position=='备用'))
                        <a onclick="modal_apply({{$certificate->id}})"><i class="la la-check-square edit"></i></a>
                    @endif
                    @if($menu=='active'&&($certificate->position!='备用'))
                        <a onclick="m_edit('replace',{{$certificate->id}})"><i class="la la-refresh edit"></i></a>
                    @endif
                    <a onclick="m_edit('certificate',{{$certificate->id}})"><i class="la la-edit edit"></i></a>
                    <a onclick="m_show('certificate',{{$certificate->id}},1)"><i class="la la-eye delete"></i></a>
                    @if(file_exists(storage_path("app\public\certificate\\$certificate->id.pdf")))
                        <a href="/download/pdf/certificate/{{$certificate->id.'/'.urlencode($certificate->order.'--'.$certificate->instrument.'--'.$certificate->number.'--【'.date('Y年m月d日',strtotime($certificate->verification_date)).'-'.date('Y年m月d日',strtotime($certificate->validity_date)).'】')}}"><i class="la la-download delete"></i></a>
                    @endif
                    <a onclick="m_delete('certificate',{{$certificate->id}})"><i class="la la-close delete"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if($menu=='active')
        <!-- 开始 使用模态框 -->
        <div id="apply" class="modal fade" data-backdrop="static">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">确认使用?</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <form action="" onsubmit="return false;" id="formApply">
                        {{method_field("put")}}
                        {{csrf_field()}}
                        <div class="modal-body">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                                <label class="form-control-label">岗位</label>
                                <select name="position" id="position" class="custom-select form-control">
                                    <option value="" selected disabled>请选择...</option>
                                    @foreach($types->where('name3',$position->level==2?$position->name:$types->find($position->pid)->name1) as $p1)
                                        <optgroup label="{{$p1->name1}}">
                                            @foreach($types->where('pid',$p1->id) as $p2)
                                                <option value="{{$p2->id}}">{{$p2->name1}}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                                <label class="form-control-label">备注</label>
                                <input type="text" name="remarks" id="remarks" class="form-control">
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <div id="btn_apply_ok"></div>
                        <button type="button" class="btn btn-secondary ripple" data-dismiss="modal">取 消</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- 结束 使用模态框 -->
        <script>
            //使用模态框
            function modal_apply(id) {
                var str = "<button class='btn btn-primary ripple' onclick='btn_apply(" + id + ")' data-dismiss='modal'>确 定</button>"
                $("#btn_apply_ok").html(str);
                $('#apply').modal('toggle')
            }

            //使用操作
            function btn_apply(id) {
                $.ajax({
                    url: "/apply/" + id,
                    type: "POST",
                    data: new FormData($("#formApply")[0]),
                    processData: false,  // 不处理数据
                    contentType: false,   // 不设置内容类型
                    success: function (result) {
                        if (result == true) {
                            document.location.reload();
                        } else if (result == false) {
                            notifications('使用失败');
                        } else {
                            notifications(result);
                        }
                    }, error: function (xhr) {
                        if (xhr.status == 401) {
                            document.location.reload();
                        } else {
                            notifications('使用失败');
                        }
                    }
                });
            }
        </script>
    @endif
@endsection

