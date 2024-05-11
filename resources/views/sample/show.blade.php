@extends('layout.show')
@section('content_title')
    <li><a class="active" data-toggle="tab" href="#number-tab" role="tab" id="number-btn">抽检详情</a></li>
@endsection
@section('content_form')
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane show active fade" id="number-tab" aria-labelledby="number-btn">
            @foreach($checks as $position=>$certificates)
                <div class="date mb-5">{{$position}}</div>
                <div class="row">
                    @foreach($certificates as $certificate)
                        <div class="col-xl-3 col-md-6 col-sm-12">
                            <div class="widget has-shadow">
                                <img src="{{str_replace('public','storage',$certificate['path'])}}" class="img-fluid" loading="lazy">
                                <div class="widget-body text-center">
                                    <h5>{{$certificate['certificate']->number}}</h5>
                                    <h5>{{$certificate['certificate']->verification_date}}</h5>
                                    <h5>{{$certificate['certificate']->validity_date}}</h5>
                                    <h5>{{$certificate['certificate']->department}}</h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
            <div class="enter-message">
                <button class="btn btn-outline-secondary ripple sidebar-close">返 回</button>
            </div>
        </div>
    </div>
@endsection
