<link rel="stylesheet" href="/admin/assets/vendors/css/timeline/timeline.css">
<div class="modal-dialog modal-full modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">查看证书</h4>
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">×</span>
                <span class="sr-only">close</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group row">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12" style="height: 720px;">
                    <div id="timeline"></div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12" style="height: 720px;">
                    <div id="pic" style="position:absolute"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/admin/assets/vendors/js/timeline/timeline.js"></script>
<script src="/admin/assets/vendors/js/nicescroll/nicescroll.min.js"></script>
<script src="/admin/assets/js/components/scrollable/scrollable.min.js"></script>
@foreach($certificates as $certificate)
    <script type="text/html" id="timeline_{{$certificate->id}}">
        <div class="row">
            <div class="form-group row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">岗位</label>
                    <input type="text" class="form-control" value="{{$certificate->position}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">序号</label>
                    <input type="text" class="form-control" value="{{$certificate->order}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">证书类型</label>
                    <input type="text" class="form-control" value="{{$certificate->category}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">器具名称</label>
                    <input type="text" class="form-control" value="{{$certificate->instrument}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">生产厂家</label>
                    <input type="text" class="form-control" value="{{$certificate->factory}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">出厂编号</label>
                    <input type="text" class="form-control" value="{{$certificate->number}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">统一编号</label>
                    <input type="text" class="form-control" value="{{$certificate->certificate_no}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">检定部门</label>
                    <input type="text" class="form-control" value="{{$certificate->department}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">检定日期</label>
                    <input type="text" class="form-control" value="{{$certificate->verification_date}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">有效期</label>
                    <input type="text" class="form-control" value="{{$certificate->validity_date}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">规格型号</label>
                    <input type="text" class="form-control" value="{{$certificate->model}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">测量范围</label>
                    <input type="text" class="form-control" value="{{$certificate->limit}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">精确度</label>
                    <input type="text" class="form-control" value="{{$certificate->accuracy}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">检定周期</label>
                    <input type="text" class="form-control" value="{{$certificate->cycle}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">ABC</label>
                    <input type="text" class="form-control" value="{{$certificate->abc}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">启用时间</label>
                    <input type="text" class="form-control" value="{{$certificate->start}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">检定次数</label>
                    <input type="text" class="form-control" value="{{$certificate->times}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">送检计划</label>
                    <input type="text" class="form-control" value="{{$certificate->plan}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">检定费用</label>
                    <input type="text" class="form-control" value="{{$certificate->money}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">检定标准</label>
                    <input type="text" class="form-control" value="{{$certificate->standard}}" readOnly>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-3">
                    <label class="form-control-label">备注</label>
                    <input type="text" class="form-control" value="{{$certificate->remark}}" readOnly>
                </div>
            </div>
        </div>
    </script>
    <script type="text/html" id="pic_{{$certificate->id}}">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            @for($page=0;$page<count($certificate->files);$page++)
                <li class="nav-item">
                    <a class="nav-link @if($page==0) active @endif" id="just-tab-{{$certificate->id}}-{{$page+1}}" data-toggle="tab" href="#j-tab-{{$certificate->id}}-{{$page+1}}" role="tab" aria-controls="j-tab-{{$certificate->id}}-{{$page+1}}" aria-selected="false">第{{$page+1}}页</a>
                </li>
            @endfor
        </ul>
        <div class="tab-content basic-scroll pt-3" style="height:680px;">
            @for($page=0;$page<count($certificate->files);$page++)
                <div class="tab-pane fade @if($page==0) show active @endif" id="j-tab-{{$certificate->id}}-{{$page+1}}" role="tabpanel" aria-labelledby="just-tab-{{$certificate->id}}-{{$page+1}}">
                    <div>
                        <img src="/{{$certificate->path}}/{{$page}}.jpg" style="max-width: 100%;">
                    </div>
                </div>
            @endfor
        </div>
    </script>
@endforeach

<script>
    @foreach($certificates as $certificate)
    var timeline_{{$certificate->id}} = $("#timeline_{{$certificate->id}}").html();
    @endforeach
        timeline = new TL.Timeline('timeline',
        {
            "events": [
                    @foreach($certificates as $certificate)
                {
                    "text": {
                        "headline": '出厂编号：{{$certificate->number}} 检定次数：{{$certificate->times}}',
                        "text": timeline_{{$certificate->id}},
                    },
                    "start_date": {
                        "year": "{{ $certificate->verification_date }}".substring(0, 4),
                        "month": "{{ $certificate->verification_date }}".substring(5, 7),
                        "day": "{{ $certificate->verification_date }}".substring(8, 10)
                    },
                    "end_date": {
                        "year": "{{ $certificate->validity_date }}".substring(0, 4),
                        "month": "{{ $certificate->validity_date }}".substring(5, 7),
                        "day": "{{ $certificate->validity_date }}".substring(8, 10)
                    },
                    "group": "岗位使用记录",
                    "unique_id": "event_{{$certificate->id}}"
                },
                @endforeach
            ],
        },
        {
            "scale_factor": 0.5,
            "start_at_slide": {{$disable}},
            "language": "zh-cn",
            "script_path": 'https://127.0.0.1:9362/admin/assets/vendors/css/timeline/',
            "timenav_mobile_height_percentage": 20,
            "track_events": ["back_to_start", "nav_next", "nav_previous", "zoom_in", "zoom_out"],
        }
    );

    t = window.setInterval(function () {
        $(".tl-text-headline-container").hide()
        if ($("#timeline").is(':visible')) {
            $("#timeline").hide()
        } else {
            $("#timeline").show()
            let MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
            let lists = $('.tl-slide-text-only');
            let arr = [
                @foreach($certificates as $certificate)
                    'event_{{$certificate->id}}',
                @endforeach
            ]
            let arr_default = arr
            let observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    if (mutation.type === 'attributes') {
                        if ($.inArray(mutation.target.id, arr) != "-1") {
                            arr.splice($.inArray(mutation.target.id, arr), 1)
                        } else {
                            arr = arr_default
                            let id = mutation.target.id.replace('event_', '')
                            $("#pic").html($("#pic_" + id).html())
                            $(".basic-scroll").niceScroll({
                                railpadding: {
                                    top: 0,
                                    right: -2,
                                    left: 0,
                                    bottom: 0
                                },
                                scrollspeed: 60,
                                zindex: 1,
                                autohidemode: "leave",
                                cursorwidth: "4px",
                                cursorcolor: "rgba(52, 40, 104, 0.2)",
                                cursorborder: "rgba(52, 40, 104, 0.2)"
                            });
                        }
                    }
                });
            });
            lists.each(function (key) {
                observer.observe(lists[key], {attributes: true,});
            })
            $("#preloader").fadeOut();
            window.clearInterval(t)
        }
    }, 800)
</script>
