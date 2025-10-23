@extends('layout.show')
@section('content_title')
    @foreach($certificates as $certificate)
        @include('template.nav-tab',['tmp_name'=>$certificate->id,'tmp_label'=>'','tmp_state'=>'hidden','tmp_active'=>$certificate->id==$disable])
    @endforeach
@endsection
@section('content_form')
    <div class="tab-content">
        @foreach($certificates as $certificate)
            <div role="tabpanel" class="tab-pane @if($certificate->id==$disable) show active @endif" id="{{$certificate->id}}-tab" aria-labelledby="{{$certificate->id}}-btn">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 row">
                        @include('template.input',['tmp_name'=>'position_id','tmp_label'=>'岗位','tmp_value'=>$certificate->position1,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'sn','tmp_label'=>'序号','tmp_value'=>$certificate->sn,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'certificate_name','tmp_label'=>'证书名称','tmp_value'=>$certificate->certificate_name,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'instrument','tmp_label'=>'器具名称','tmp_value'=>$certificate->instrument,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'factory','tmp_label'=>'生产厂家','tmp_value'=>$certificate->factory,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'number','tmp_label'=>'出厂编号','tmp_value'=>$certificate->number,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'verification_date','tmp_label'=>'检定日期','tmp_value'=>$certificate->verification_date,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'validity_date','tmp_label'=>'有效期','tmp_value'=>$certificate->validity_date,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'department','tmp_label'=>'检定部门','tmp_value'=>$certificate->department,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'model','tmp_label'=>'规格型号','tmp_value'=>$certificate->model,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'limit','tmp_label'=>'测量范围','tmp_value'=>$certificate->limit,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'accuracy','tmp_label'=>'精确度','tmp_value'=>$certificate->accuracy,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'cycle','tmp_label'=>'检定周期','tmp_value'=>$certificate->cycle,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'abc','tmp_label'=>'ABC','tmp_value'=>$certificate->abc,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'plan','tmp_label'=>'检定计划','tmp_value'=>$certificate->plan,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'certificate_no','tmp_label'=>'统一编号','tmp_value'=>$certificate->certificate_no,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'category','tmp_label'=>'证书类型','tmp_value'=>$certificate->category,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'standard','tmp_label'=>'检定标准','tmp_value'=>$certificate->standard,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'start_date','tmp_label'=>'开始日期','tmp_value'=>$certificate->start_date,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'end_date','tmp_label'=>'结束日期','tmp_value'=>$certificate->end_date,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'start','tmp_label'=>'启用时间','tmp_value'=>$certificate->start,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'receiving_date','tmp_label'=>'领用日期','tmp_value'=>$certificate->receiving_date,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'receiver','tmp_label'=>'领用人','tmp_value'=>$certificate->receiver,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'times','tmp_label'=>'检定次数','tmp_value'=>$certificate->times,'tmp_state'=>'readonly','tmp_col'=>'4'])
                        @include('template.input',['tmp_name'=>'remark','tmp_label'=>'备注','tmp_value'=>$certificate->remark,'tmp_state'=>'readonly','tmp_col'=>'4'])
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            @for($page=0;$page<count($certificate->files);$page++)
                                @include('template.nav-tab',['tmp_name'=>$certificate->id.'-'.($page+1),'tmp_label'=>$page+1,'tmp_active'=>$page==0])
                            @endfor
                        </ul>
                        <div class="tab-content d-flex overflow-auto">
                            @for($page=0;$page<count($certificate->files);$page++)
                                <div role="tabpanel" class="tab-pane @if($page==0) show active @endif" id="{{$certificate->id}}-{{$page+1}}-tab" aria-labelledby="{{$certificate->id}}-{{$page+1}}-btn">
                                    <img class="w-100" src="/{{$certificate->path}}/{{$page}}.jpg" loading="lazy">
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
                @include('template.sidebar-btn')
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
                    $('#' + properties.items[0] + '-btn').click()
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
