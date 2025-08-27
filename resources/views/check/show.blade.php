@extends('layout.show')
@section('content_title')
    <li><a class="active" data-toggle="tab" href="#number-tab" role="tab" id="number-btn">抽检详情</a></li>
@endsection
@section('content_form')
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane show active fade" id="number-tab" aria-labelledby="number-btn">
            @foreach($checks as $month=>$files)
                <div class="date mb-5">{{$month}}</div>
                <div class="row">
                    @foreach($files as $file)
                        <div class="col-xl-3 col-md-6 col-sm-12">
                            <div class="widget has-shadow">
                                <img src="{{str_replace('public','storage',$file['path'])}}" class="img-fluid" loading="lazy">
                                <div class="widget-body text-center">
                                    <h5>{{str_replace("'",':',substr($file['path'], strrpos($file['path'], '/') + 1, 19))}}</h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection
