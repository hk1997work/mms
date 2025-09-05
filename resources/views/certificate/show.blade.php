@extends('layout.show')
@section('content_title')
    @foreach($certificates as $certificate)
        @include('template.nav-tab',['name'=>$certificate->id,'label'=>$certificate->id,'active'=>$loop->first])
    @endforeach
@endsection
@section('content_form')
    <div class="tab-content">
        @foreach($certificates as $certificate)
            <div role="tabpanel" class="tab-pane @if($certificate->id==$disable) show active @endif" id="{{$certificate->id}}-tab" aria-labelledby="{{$certificate->id}}-btn">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 row">
                        @include('template.input',['name'=>'position_id','label'=>'岗位','value'=>$certificate->position1,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'sn','label'=>'序号','value'=>$certificate->sn,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'certificate_name','label'=>'证书名称','value'=>$certificate->certificate_name,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'instrument','label'=>'器具名称','value'=>$certificate->instrument,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'factory','label'=>'生产厂家','value'=>$certificate->factory,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'number','label'=>'出厂编号','value'=>$certificate->number,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'verification_date','label'=>'检定日期','value'=>$certificate->verification_date,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'validity_date','label'=>'有效期','value'=>$certificate->validity_date,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'department','label'=>'检定部门','value'=>$certificate->department,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'model','label'=>'规格型号','value'=>$certificate->model,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'limit','label'=>'测量范围','value'=>$certificate->limit,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'accuracy','label'=>'精确度','value'=>$certificate->accuracy,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'cycle','label'=>'检定周期','value'=>$certificate->cycle,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'abc','label'=>'ABC','value'=>$certificate->abc,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'plan','label'=>'检定计划','value'=>$certificate->plan,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'certificate_no','label'=>'统一编号','value'=>$certificate->certificate_no,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'category','label'=>'证书类型','value'=>$certificate->category,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'standard','label'=>'检定标准','value'=>$certificate->standard,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'start','label'=>'启用时间','value'=>$certificate->start,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'times','label'=>'检定次数','value'=>$certificate->times,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'remark','label'=>'备注','value'=>$certificate->remark,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'start_date','label'=>'开始日期','value'=>$certificate->start_date,'col'=>'4','state'=>'readonly'])
                        @include('template.input',['name'=>'end_date','label'=>'结束日期','value'=>$certificate->end_date,'col'=>'4','state'=>'readonly'])
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            @for($page=0;$page<count($certificate->files);$page++)
                                @include('template.nav-tab',['name'=>$certificate->id.'-'.($page+1),'label'=>$page+1,'active'=>$page==0])
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
                @include('template.btn')
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
