@extends("layout.main")
@section("content")
    <div class="row flex-row mt-4">
        <div class="col-xl-4 col-md-4">
            <div class="widget-32 widget-image bg-image has-shadow">
                <div class="overlay"></div>
                <div class="content">
                    <div id="events-day"></div>
                    <div id="events-date"></div>
                    <div id="events-year"></div>
                </div>
                <div class="real-time">
                    <div id="events-time"></div>
                </div>
            </div>
            <div class="widget widget-22 bg-gradient-03 has-shadow">
                <div class="widget-body h-100 d-flex align-items-center">
                    <div class="section-title">
                        <h3>电子证书</h3>
                    </div>
                    <div class="home_per certificate_per">
                        <div class="percent"></div>
                    </div>
                    <b class="value-progress certificate_count"></b>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-md-8">
            <div class="widget widget-09 has-shadow">
                <div class="widget-body">
                    <div class="today">
                        <div class="title">{{date('Y')}}</div>
                        <div class="new-tasks mt-2"><span class="nb">周检计划</span></div>
                    </div>
                    <div class="row">
                        <div class="col-xl-10 col-12 no-padding">
                            <div>
                                <canvas id="orders"></canvas>
                            </div>
                        </div>
                        <div class="col-xl-2 col-12 my-auto no-padding text-center row">
                            <div class="col-xl-12 col-lg-6 col-md-6 col-sm-6">
                                <div class="new-orders">
                                    <div class="circle-orders">
                                        <div class="percent-orders"></div>
                                    </div>
                                    <div class="title">{{date('m')}}月完成率</div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-6 col-md-6 col-sm-6">
                                <div class="some-stats mt-5">
                                    <div class="number text-danger" id="sj">{{$year_plan[0]->sj}}</div>
                                    <div class="title">{{date('m')}}月待送检</div>
                                </div>
                                <div class="some-stats mt-3">
                                    <div class="number text-danger" id="bj">{{$year_plan[0]->bj}}</div>
                                    <div class="title">{{date('m')}}月待报检</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row flex-row">
        <div class="col-xl-4 col-md-6">
            <div class="widget widget-14 has-shadow">
                <div class="widget-body">
                    <div class="today">
                        <div class="title">{{$plans->where('type','报检')->count()}}类{{$plans->where('type','报检')->sum('total')}}件</div>
                        <div class="new-tasks mt-2"><span class="nb">报检清单</span></div>
                    </div>
                    <ul class="list-group w-100 widget-scroll mt-3" style="max-height: 400px; overflow:hidden;">
                        @foreach($plans->where('type','报检') as $plan)
                            <li class="list-group-item mr-3">
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
        <div class="col-xl-4 col-md-6">
            <div class="widget widget-14 has-shadow">
                <div class="widget-body">
                    <div class="today">
                        <div class="title">{{$plans->where('type','送检')->count()}}类{{$plans->where('type','送检')->sum('total')}}件</div>
                        <div class="new-tasks mt-2"><span class="nb">送检清单</span></div>
                    </div>
                    <ul class="list-group w-100 widget-scroll mt-3" style="max-height: 400px; overflow:hidden;">
                        @foreach($plans->where('type','送检') as $plan)
                            <li class="list-group-item mr-3">
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
        <div class="col-xl-4 col-md-6">
            <div class="widget widget-14 has-shadow">
                <div class="widget-body">
                    <div class="today">
                        <div class="title">{{$standbys->count()}}类</div>
                        <div class="new-tasks mt-2"><span class="nb">备用清单</span></div>
                    </div>
                    <ul class="list-group w-100 widget-scroll mt-3" style="max-height: 400px; overflow:hidden;">
                        @foreach($standbys as $standby)
                            <li class="list-group-item mr-3">
                                <div class="media list-group-item shadow-sm
                                @switch($plan->plan)
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
                                            <span>{{$standby->department}}</span>
                                        </div>
                                    </div>
                                    <div class="event-date align-self-center @if($standby->standby-$standby->danger>0) text-success @else text-danger @endif">{{$standby->inuse}}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="widget widget-13 has-shadow">
                <div class="widget-body pt-0">
                    <div class="author-name">
                        <span>今日抽检</span>
                        {{$check_count['day']}}
                    </div>
                    <div class="follow-btn text-center mt-3">
                        <a class="btn btn-outline-primary btn-copy" href="#" @if($check_count['day']==0) hidden @endif>复 制</a>
                        <a class="btn btn-outline-primary btn-show" href="#" data-id="{{date('Y-m-d')}}" @if($check_count['day']==0) hidden @endif>查 看</a>
                        <div id="text-copy" hidden>{!! $copy !!}</div>
                    </div>
                    <div class="author-name">
                        <span>抽检</span>
                    </div>
                    <div class="social-stats">
                        <div class="row d-flex justify-content-between">
                            <div class="col-6 text-center">
                                <div class="counter">{{$check_count['year']}}</div>
                                <div class="heading">{{date('Y')}}年</div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="counter">{{$check_count['month_total']}}</div>
                                <div class="heading">{{date('m')}}月</div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-between mt-2">
                            @foreach($positions as $position)
                                <div class="col-4 text-center">
                                    <div class="counter">{{$check_count['month'][$position->name]}}</div>
                                    <div class="heading">{{$position->name}}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="author-name">
                        <span>周检</span>
                    </div>
                    <div class="social-stats mb-5">
                        <div class="row d-flex justify-content-between">
                            <div class="col-6 text-center">
                                <div class="counter">{{$certificates->where('verification_date', '>=', $start_year)->where('verification_date', '<=', $end_year)->count()}}</div>
                                <div class="heading">{{date('Y')}}年</div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="counter">{{$certificates->where('verification_date', '>=',$start_month)->where('verification_date', '<=',$end_month)->whereIn('unit2',$positions->pluck('name'))->count()}}</div>
                                <div class="heading">{{date('m')}}月</div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-between mt-2">
                            @foreach($positions as $position)
                                <div class="col-4 text-center">
                                    <div class="counter">{{$certificates->where('verification_date', '>=', $start_month)->where('verification_date', '<=', $end_month)->where('unit2',$position->name)->count()}}</div>
                                    <div class="heading">{{$position->name}}</div>
                                </div>
                            @endforeach
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
    <script src="/admin/assets/vendors/js/chart/chart.min.js"></script>
    <script src="/admin/assets/vendors/js/progress/circle-progress.min.js"></script>

    <script src="/admin/assets/js/dashboard/main.js"></script>
@endpush
