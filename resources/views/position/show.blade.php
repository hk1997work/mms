<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{$position->name}}岗位量具配备情况</h4>
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">×</span>
                <span class="sr-only">close</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row flex-row mt-3">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                    <div class="widget widget-16 has-shadow">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                    <b class="counter text-success">{{$certificates->where('valid',1)->count()}}</b>
                                    <b class="total-visitors text-center">有效</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                    <div class="widget widget-16 has-shadow">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                    <b class="counter text-danger">{{$certificates->where('valid',0)->count()}}</b>
                                    <b class="total-visitors text-center">失效</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive" style="height:500px;">
                <table id="modal-table" class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>岗位</th>
                        <th>器具名称</th>
                        <th>启用时间</th>
                        <th>检定次数</th>
                        <th>管理状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($certificates as $certificate)
                        <tr class="info-tool" data-id="{{$certificate->tool_id}}">
                            <td>{{$certificate->order}}</td>
                            <td>{{$certificate->position}}</td>
                            <td>{{$certificate->instrument}}</td>
                            <td>{{$certificate->start}}</td>
                            <td>{{$certificate->times}}</td>
                            <td>@if($certificate->valid) <div class="text-success">有效</div> @else <div class="text-danger">失效</div> @endif</td>
                            <td class="td-actions">
                                <a onclick="up_modal('sn',{{$certificate->id}})"><i class="la la-angle-up edit"></i></a>
                                <a onclick="down_modal('sn',{{$certificate->id}})"><i class="la la-angle-down edit"></i></a>
                                <a onclick="m_edit('sn',{{$certificate->id}})"><i class="la la-edit edit"></i></a>
                                <a onclick="m_show('certificate',{{$certificate->id}},1)"><i class="la la-eye delete"></i></a>
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
