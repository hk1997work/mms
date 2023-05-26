<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">岗位量具配备情况</h4>
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
                                    <b class="counter text-warning">{{$certificates->groupBy('tool_id')->count()}}</b>
                                    <b class="total-visitors text-center">种类</b>
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
                                    <b class="counter text-success">{{$certificates->count()}}</b>
                                    <b class="total-visitors text-center">数量</b>
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
                        <th>器具名称</th>
                        <th>规格型号</th>
                        <th>数量</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($certificates as $certificate)
                        @if($loop->index==0 or $certificate->tool_id!= $certificates[$loop->index-1]->tool_id)
                            <tr class="info-tool" data-id="{{$certificate->tool_id}}">
                                <td style="width: 40%">{{$certificate->instrument}}</td>
                                <td style="width: 35%">{{$certificate->model}}</td>
                                <td style="width: 25%">
                                    @if($position->leve==1)
                                        {{$certificates->where('tool_id',$certificate->tool_id)->where('unit4_id',$certificate->unit4_id)->count()}}
                                    @elseif($position->level==2)
                                        {{$certificates->where('tool_id',$certificate->tool_id)->where('unit3_id',$certificate->unit3_id)->count()}}
                                    @elseif($position->level==3)
                                        {{$certificates->where('tool_id',$certificate->tool_id)->where('unit2_id',$certificate->unit2_id)->count()}}
                                    @elseif($position->level==4)
                                        {{$certificates->where('tool_id',$certificate->tool_id)->where('unit1_id',$certificate->unit1_id)->count()}}
                                    @elseif($position->level==5)
                                        {{$certificates->where('tool_id',$certificate->tool_id)->where('position_id',$certificate->position_id)->count()}}
                                    @endif
                                </td>
                            </tr>
                            <tr class="info-number info-number{{$certificate->tool_id}}" style="display: none ;border-bottom: 1px solid;">
                                <td class="text-primary">生产厂家</td>
                                <td class="text-primary">出厂编号</td>
                                <td></td>
                            </tr>
                        @endif
                        <tr class="info-number info-number{{$certificate->tool_id}}" style="display: none;border-bottom: 1px solid;">
                            <td>{{$certificate->factory}}</td>
                            <td>{{$certificate->number}}</td>
                            <td class="td-actions"><a onclick="m_show('certificate',{{$certificate->id}},1)"><i class="la la-eye delete"></i></a></td>
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
<script>
    $(".info-tool").click(function () {
        let id = $(this).attr("data-id")
        if ($(".info-number" + id).is(':hidden')) {
            $(".info-number").hide()
            $(".info-number" + id).toggle()
        } else {
            $(".info-number").hide()
        }
    })
</script>
