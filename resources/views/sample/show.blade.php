@extends('layout.show')
@section('content_title')
    @include('template.nav-tab',['tmp_name'=>'number','tmp_label'=>'抽检详情','tmp_active'=>true])
@endsection
@section('content_form')
    <div class="tab-content">
        @foreach($checks as $position=>$certificates)
            <h3 class="text-center my-3">{{$position}}</h3>
            <div class="row">
                @foreach($certificates as $certificate)
                    <div class="col-xl-3 col-md-6 col-sm-12">
                        <form action="" onsubmit="return false;">
                            {{method_field('delete')}}
                            {{csrf_field()}}
                            <div>
                                <img src="{{str_replace('public','storage',$certificate->filepath)}}" class="img-fluid" loading="lazy">
                                <div class="text-center my-2">
                                    <div>{{$certificate->number}}</div>
                                    <div>{{$certificate->verification_date}}</div>
                                    <div>{{$certificate->validity_date}}</div>
                                    <div>{{$certificate->department}}</div>
                                    @include('template.btn',['tmp_class'=>'submit-delete','tmp_color'=>'danger','tmp_label'=>'删 除','tmp_id'=>'true'])
                                    <input type="hidden" name="filepath" value="{{$certificate->filepath}}">
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
