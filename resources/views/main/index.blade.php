@extends("layout.main")
@section("content")
    <div class="swiper" id="swiper-main">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="row flex-row">
                    <div class="col-xl-3 col-md-3">
                        <div class="circle-certificate bg-body-tertiary rounded-4 shadow-sm my-3 h-100">
                            <div class="container-fluid box-body h-100 d-flex align-items-center" title="{{$check_pdf}}">
                                <div class="box-title">电子证书</div>
                                <div class="circle"><strong class="circle-border"></strong></div>
                                <div class="box-remark">{{$pdf_count}}/{{$certificates->count()}}</div>
                            </div>
                        </div>
                        <div class="circle-certificate bg-body-tertiary rounded-4 shadow-sm my-3 h-100">
                            <div class="container-fluid box-body h-100 d-flex align-items-center" title="{{$check_pdf}}">
                                <div class="box-title">未知</div>
                                <div class="circle"><strong class="circle-border"></strong></div>
                                <div class="box-remark">0/0</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="bg-body-tertiary rounded-4 shadow-sm my-3 h-100">
                            <div class="container-fluid box-body">
                                <div class="box-title">
                                    {{date('Y')}}
                                    <div>周检计划</div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-9 col-12 mt-5">
                                        <canvas id="chart-plan"></canvas>
                                    </div>
                                    <div class="col-xl-3 col-12 my-auto no-padding text-center row">
                                        <div class="circle-plan col-xl-12 col-lg-7 col-md-7 col-sm-7 no-padding">
                                            <div class="circle"><strong></strong></div>
                                            <div>{{date('m')}}月<br>完成率</div>
                                        </div>
                                        <div class="col-xl-12 col-lg-5 col-md-5 col-sm-5 no-padding">
                                            <div class="my-3">
                                                <div class="text-danger">{{$year_plan[0]->sj}}</div>
                                                待送检
                                            </div>
                                            <div class="my-3">
                                                <div class="text-danger">{{$year_plan[0]->bj}}</div>
                                                待报检
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-3">
                        <div class="bg-body-tertiary rounded-4 shadow-sm my-3 h-100">
                            <div class="container-fluid text-center my-3">
                                <div>今日抽检</div>
                                {{$check_count['day']}}
                                <div class="row">
                                    @foreach($positions as $position)
                                        <div class="no-padding my-2">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: {{$check_count[$position]['success']/$check_count[$position]['total']*100}}%">{{$check_count[$position]['success']}}</div>
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: {{$check_count[$position]['info']/$check_count[$position]['total']*100}}%">{{$check_count[$position]['info']}}</div>
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: {{$check_count[$position]['warning']/$check_count[$position]['total']*100}}%">{{$check_count[$position]['warning']}}</div>
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: {{$check_count[$position]['danger']/$check_count[$position]['total']*100}}%">{{$check_count[$position]['danger']}}</div>
                                            </div>
                                            <div>{{$position}}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="my-3">
                                    <a class="btn btn-sm btn-outline-primary btn-copy" href="#" @if($check_count['day']==0) hidden @endif>复 制</a>
                                    <a class="btn btn-sm btn-outline-primary btn-show" href="#" data-id="{{date('Y-m-d')}}" @if($check_count['day']==0) hidden @endif>查 看</a>
                                    <div id="text-copy" hidden>{!! $copy !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row flex-row">

                    <div class="col-xl-3 col-md-3">
                        <div class="bg-body-tertiary rounded-4 shadow-sm my-3 h-100">
                            <div class="container-fluid text-center my-3">
                                <div>抽检详情</div>
                                <div class="row">
                                    <div class="col-6">
                                        {{$check_count['year']}}
                                        <div>{{date('Y')}}年</div>
                                    </div>
                                    <div class="col-6">
                                        {{$check_count['month_total']}}
                                        <div>{{date('m')}}月</div>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach($positions as $position)
                                        <div class="col-4">
                                            <div>{{$check_count['month'][$position]['count']}}</div>
                                            <div>{{$position}}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-3">
                        <div class="bg-body-tertiary rounded-4 shadow-sm my-3 h-100">
                            <div class="container-fluid text-center my-3">
                                <div>周检详情</div>
                                <div class="row">
                                    <div class="col-6">
                                        {{$certificates->where('verification_date', '>=', $start_year)->where('verification_date', '<=', $end_year)->count()}}
                                        <span>{{date('Y')}}年</span>
                                    </div>
                                    <div class="col-6">
                                        {{$certificates->where('verification_date', '>=',$start_month)->where('verification_date', '<=',$end_month)->whereIn('unit2',$positions->pluck('name'))->count()}}
                                        <span>{{date('m')}}月</span>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach($positions as $position)
                                        <div class="col-4">
                                            <div>{{$certificates->where('verification_date', '>=', $start_month)->where('verification_date', '<=', $end_month)->where('unit2',$position)->count()}}</div>
                                            <div>{{$position}}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="row flex-row">
                    <div class="col-xl-4 col-md-4">
                        <div class="widget widget-14 has-shadow">
                            <div class="widget-body">
                                <div class="today">
                                    <div class="title">{{$plans->where('type','报检')->count()}}类{{$plans->where('type','报检')->sum('total')}}件</div>
                                    <div class="new-tasks mt-2"><span class="nb">报检清单</span></div>
                                </div>
                                <ul class="list-group hidden-scroll mt-3">
                                    @foreach($plans->where('type','报检') as $plan)
                                        <li class="list-group-item">
                                            <div class="media list-group-item shadow-sm
                                @switch($plan->plan)
                                        @case('市计量院') fc-bg-green @break
                                        @case('省计量院') fc-bg-blue @break
                                        @case('钢研纳克') fc-bg-violet @break
                                        @default fc-bg-orange @break
                                        @endswitch">
                                                <div class="media-body align-self-center">
                                                    <div class="event-title @if($plan->diff_date<=0) text-danger @endif">
                                                        {{$plan->instrument}}
                                                        @if(isset($plan->danger)||isset($plan->warning)||isset($plan->success))
                                                            @if($plan->warning>0)
                                                                <span class="text-warning">{{$plan->warning}}</span>
                                                            @endif
                                                            @if($plan->success>0)
                                                                <span class="text-success">{{$plan->success}}</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="event-desc">
                                                        <i class="la la-sitemap"></i>
                                                        <span>{!! $plan->str !!}</span>
                                                    </div>
                                                </div>
                                                <div class="event-date align-self-center @if($plan->total-$plan->warning-$plan->success<=0) text-success @else text-danger @endif">
                                                    {{$plan->total}}
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4">
                        <div class="widget widget-14 has-shadow">
                            <div class="widget-body">
                                <div class="today">
                                    <div class="title">{{$plans->where('type','送检')->count()}}类{{$plans->where('type','送检')->sum('total')}}件</div>
                                    <div class="new-tasks mt-2"><span class="nb">送检清单</span></div>
                                </div>
                                <ul class="list-group hidden-scroll mt-3" style="overflow:hidden;">
                                    @foreach($plans->where('type','送检') as $plan)
                                        <li class="list-group-item">
                                            <div class="media list-group-item shadow-sm
                                @switch($plan->plan)
                                        @case('市计量院') fc-bg-green @break
                                        @case('省计量院') fc-bg-blue @break
                                        @case('钢研纳克') fc-bg-violet @break
                                        @default fc-bg-orange @break
                                        @endswitch">
                                                <div class="media-body align-self-center">
                                                    <div class="event-title @if($plan->diff_date<=0) text-danger @endif">
                                                        {{$plan->instrument}}
                                                        @if(isset($plan->danger)||isset($plan->warning)||isset($plan->success))
                                                            @if($plan->warning>0)
                                                                <span class="text-warning">{{$plan->warning}}</span>
                                                            @endif
                                                            @if($plan->success>0)
                                                                <span class="text-success">{{$plan->success}}</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="event-desc">
                                                        <i class="la la-sitemap"></i>
                                                        <span>{!! $plan->str !!}</span>
                                                    </div>
                                                </div>
                                                <div class="event-date align-self-center @if($plan->total-$plan->warning-$plan->success<=0) text-success @else text-danger @endif">
                                                    {{$plan->total}}
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4">
                        <div class="widget widget-14 has-shadow">
                            <div class="widget-body">
                                <div class="today">
                                    <div class="title">{{$standbys->count()}}类</div>
                                    <div class="new-tasks mt-2"><span class="nb">备用清单</span></div>
                                </div>
                                <ul class="list-group hidden-scroll mt-3">
                                    @foreach($standbys as $standby)
                                        <li class="list-group-item">
                                            <div class="media list-group-item shadow-sm
                                    @switch($standby->plan)
                                        @case('市计量院') fc-bg-green @break
                                        @case('省计量院') fc-bg-blue @break
                                        @case('钢研纳克') fc-bg-violet @break
                                        @default fc-bg-orange @break
                                    @endswitch">
                                                <div class="media-body align-self-center">
                                                    <div class="event-title @if($standby->standby-$standby->danger<=0) text-danger @endif">
                                                        {{$standby->instrument}}
                                                        @if(isset($standby->danger)||isset($standby->warning)||isset($standby->success))
                                                            @if($standby->danger>0)
                                                                <span class="text-danger">{{$standby->danger}}</span>
                                                            @endif
                                                            @if($standby->warning>0)
                                                                <span class="text-warning">{{$standby->warning}}</span>
                                                            @endif
                                                            @if($standby->success>0)
                                                                <span class="text-success">{{$standby->success}}</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="event-desc">
                                                        <i class="la la-sitemap"></i>
                                                        <span>{!! $standby->str !!}</span>
                                                    </div>
                                                </div>
                                                <div class="event-date align-self-center @if($standby->standby-$standby->danger>0) text-success @else text-danger @endif">
                                                    {{$standby->total}}
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page-js-after')
    <script>
        let pdf_count = {{$pdf_count}};
        let pdf_total = {{$certificates->count()}};
        let year_plan = {!! json_encode($year_plan) !!};
        let month_plan = {{$month_plan}};
    </script>
    <script src="/admin/assets/js/pages/main.js"></script>
@endpush
