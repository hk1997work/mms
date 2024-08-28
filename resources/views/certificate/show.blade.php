@extends('layout.show')
@section('content_title')
    @foreach($certificates as $certificate)
        <li><a data-toggle="tab" href="#tab-{{$certificate->id}}" role="tab" id="btn-{{$certificate->id}}" hidden></a></li>
    @endforeach
@endsection
@section('content_form')
    <div class="tab-content">
        @foreach($certificates as $certificate)
            <div role="tabpanel" class="tab-pane @if($certificate->id==$disable) show active @endif fade" id="tab-{{$certificate->id}}" aria-labelledby="btn-{{$certificate->id}}">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 row">
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">岗位</div>
                            <input type="text" class="form-control" value="{{$certificate->position}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">序号</div>
                            <input type="text" class="form-control" value="{{$certificate->sn}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">证书名称</div>
                            <input type="text" class="form-control" value="{{$certificate->certificate_name}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">器具名称</div>
                            <input type="text" class="form-control" value="{{$certificate->instrument}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">生产厂家</div>
                            <input type="text" class="form-control" value="{{$certificate->factory}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">出厂编号</div>
                            <input type="text" class="form-control" value="{{$certificate->number}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">检定日期</div>
                            <input type="text" class="form-control" value="{{$certificate->verification_date}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">有效期</div>
                            <input type="text" class="form-control" value="{{$certificate->validity_date}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">检定部门</div>
                            <input type="text" class="form-control" value="{{$certificate->department}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">规格型号</div>
                            <input type="text" class="form-control" value="{{$certificate->model}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">测量范围</div>
                            <input type="text" class="form-control" value="{{$certificate->limit}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">精确度</div>
                            <input type="text" class="form-control" value="{{$certificate->accuracy}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">检定周期</div>
                            <input type="text" class="form-control" value="{{$certificate->cycle}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">ABC</div>
                            <input type="text" class="form-control" value="{{$certificate->abc}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">检定计划</div>
                            <input type="text" class="form-control" value="{{$certificate->plan}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">统一编号</div>
                            <input type="text" class="form-control" value="{{$certificate->certificate_no}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">证书类型</div>
                            <input type="text" class="form-control" value="{{$certificate->category}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">检定标准</div>
                            <input type="text" class="form-control" value="{{$certificate->standard}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">启用时间</div>
                            <input type="text" class="form-control" value="{{$certificate->start}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">检定次数</div>
                            <input type="text" class="form-control" value="{{$certificate->times}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">备注</div>
                            <input type="text" class="form-control" value="{{$certificate->remark}}{{$certificate->number_remark}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">开始日期</div>
                            <input type="text" class="form-control" value="{{$certificate->start_date}}" readonly>
                        </div>
                        <div class="col-4">
                            <div class="sidebar-heading mt-3 mb-2">结束日期</div>
                            <input type="text" class="form-control" value="{{$certificate->end_date}}" readonly>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div>
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                @for($page=0;$page<count($certificate->files);$page++)
                                    <li class="nav-item">
                                        <a class="nav-link @if($page==0) active @endif" id="just-tab-{{$certificate->id}}-{{$page+1}}" data-toggle="tab" href="#j-tab-{{$certificate->id}}-{{$page+1}}" role="tab">{{$page+1}}</a>
                                    </li>
                                @endfor
                            </ul>
                            <div class="tab-content basic-scroll pt-3" style="height: auto;overflow: auto">
                                @for($page=0;$page<count($certificate->files);$page++)
                                    <div class="tab-pane fade @if($page==0) show active @endif" id="j-tab-{{$certificate->id}}-{{$page+1}}" role="tabpanel" aria-labelledby="just-tab-{{$certificate->id}}-{{$page+1}}">
                                        <div class="div-img">
                                            <img src="/{{$certificate->path}}/{{$page}}.jpg" style="max-width: 100%;min-height: 570px" loading="lazy">
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                <div class="enter-message">
                    <button class="btn btn-outline-secondary ripple sidebar-close">取 消</button>
                </div>
            </div>
        @endforeach
        <div class="col-12 mt-2" id="timeline"></div>
    </div>
    <script>
        $(document).ready(function () {
            let container = document.getElementById('timeline');
            let arr = []
            @foreach($certificates as $certificate)
            arr.push(
                {
                    id: {{ $certificate->id }},
                    content: '{{ $certificate->number }}',
                    start: "{{ $certificate->start_date }} 00:00:00",
                    end: "{{ $certificate->end_date }} 23:59:59",
                })
            @endforeach
            let items = new vis.DataSet(arr);
            let options = {
                height: '100px',
                stack: false,
                min: "{{ $min }}",
                max: "{{ $max }}",
            };
            let selected
            let timeline = new vis.Timeline(container, items, options);
            timeline.setSelection([{{$disable}}]);
            timeline.on('select', function (properties) {
                if (properties.items.length > 0) {
                    $('#btn-' + properties.items[0]).click()
                    selected = properties.items[0]
                }
                if (properties.items.length == 0) {
                    timeline.setSelection([selected]);
                }
            });
            if ($(window).height() < $(window).width()) {
                $('.tab-content').height($('.off-sidebar-content').height() - 160)
            }
        });
    </script>
@endsection
