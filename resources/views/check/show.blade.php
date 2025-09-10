@extends('layout.show')
@section('content_title')
    @include('template.nav-tab',['tmp_name'=>'number','tmp_label'=>'抽检详情','tmp_active'=>true])
@endsection
@section('content_form')
    <div class="tab-content">
        @foreach($checks as $month=>$files)
            <h3 class="text-center my-3">{{$month}}</h3>
            <div class="row">
                @foreach($files as $file)
                    <div class="col-xl-3 col-md-6 col-sm-12">
                        <div>
                            <img src="{{str_replace('public','storage',$file['path'])}}" class="img-fluid" loading="lazy">
                            <div class="text-center my-2">
                                <div>{{str_replace("'",':',substr($file['path'], strrpos($file['path'], '/') + 1, 19))}}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
        @include('template.btn')
    </div>
@endsection
