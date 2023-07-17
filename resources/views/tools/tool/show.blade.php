<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">量具管理</h4>
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">×</span>
                <span class="sr-only">close</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row mt-3">
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 d-none d-sm-block">
                    <div class="widget widget-16 has-shadow">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                    <b class="counter text-success">{{$numbers->where('state','在用')->count()}}</b>
                                    <b class="total-visitors text-center">在用</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 d-none d-sm-block">
                    <div class="widget widget-16 has-shadow">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                    <b class="counter text-info">{{$numbers->where('state','备用')->count()}}</b>
                                    <b class="total-visitors text-center">备用</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 d-none d-sm-block">
                    <div class="widget widget-16 has-shadow">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                    <b class="counter text-warning">{{$numbers->where('state','待检')->count()}}</b>
                                    <b class="total-visitors text-center">待检</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 d-none d-sm-block">
                    <div class="widget widget-16 has-shadow">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                    <b class="counter text-primary">{{$numbers->where('state','封存')->count()}}</b>
                                    <b class="total-visitors text-center">封存</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 d-none d-sm-block">
                    <div class="widget widget-16 has-shadow">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                    <b class="counter text-danger">{{$numbers->where('state','损坏')->count()}}</b>
                                    <b class="total-visitors text-center">损坏</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 d-none d-sm-block">
                    <div class="widget widget-16 has-shadow">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                    <b class="counter text-dark">{{$numbers->where('state','报废')->count()}}</b>
                                    <b class="total-visitors text-center">报废</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive" style="max-height:500px;">
                <table id="unsorted-table" class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th>生产厂家</th>
                        <th>出厂编号</th>
                        <th>使用状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($numbers as $number)
                        @if($loop->index==0 or $number->factory_id!= $numbers[$loop->index-1]->factory_id)
                            <tr @if($number->certificate_id==null) class="bg-error" @endif>
                                <td>{{$number->factory}}</td>
                                <td><span class="btn btn-outline-secondary btn-sm ripple" onclick="m_add('number',{{$number->factory_id}})">增加编号</span></td>
                                <td></td>
                                <td class="td-actions">
                                    <a onclick="m_edit('factory',{{$number->factory_id}})"><i class="la la-edit edit"></i></a>
                                    <a onclick="m_delete('factory',{{$number->factory_id}})"><i class="la la-close delete"></i></a>
                                </td>
                            </tr>
                        @endif
                        @if($number->id!=null)
                            <tr @if($number->certificate_id==null) class="bg-error" @endif>
                                <td>{{$number->factory}}</td>
                                <td @if($number->overtime) class="bg-error" @endif>{{$number->number}}</td>
                                <td @if($number->mistake) class="bg-error" @endif><span @if(isset($number->certificate_id)) onclick="m_show('certificate',{{$number->certificate_id}},1)" @endif class="btn
                                @switch($number->state)
                                    @case('在用') btn-outline-success @break
                                    @case('备用') btn-outline-info @break
                                    @case('待检') btn-outline-warning @break
                                    @case('封存') btn-outline-primary @break
                                    @case('损坏') btn-outline-danger @break
                                    @case('报废') btn-outline-dark @break
                                    @endswitch btn-sm ripple">{{$number->state}}</span>
                                </td>
                                <td class="td-actions">
                                    <a onclick="m_edit('number',{{$number->id}})"><i class="la la-edit edit"></i></a>
                                    <a onclick="m_delete('number',{{$number->id}})"><i class="la la-close delete"></i></a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="m_add('factory',{{$tool->id}})" class="btn btn-primary ripple">增加厂家</button>
            <button type="button" class="btn btn-secondary ripple" data-dismiss="modal">返 回</button>
        </div>
    </div>
</div>
