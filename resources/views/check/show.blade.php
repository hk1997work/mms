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
                        <form action="" onsubmit="return false;">
                            {{method_field('delete')}}
                            {{csrf_field()}}
                            <div>
                                <img src="{{str_replace('public','storage',$file)}}" class="img-fluid" loading="lazy">
                                <div class="text-center my-2">
                                    <div>{{str_replace("'",':',substr($file, strrpos($file, '/') + 1, 19))}}</div>
                                    @include('template.btn',['tmp_class'=>'submit-delete','tmp_color'=>'danger','tmp_label'=>'删 除'])
                                    <input type="hidden" name="filepath" value="{{$file}}">
                                </div>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        @endforeach
        @include('template.sidebar-btn')
    </div>
@endsection
