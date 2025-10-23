@extends('layout.show')
@section('content_title')
    @include('template.nav-tab',['tmp_name'=>'supervision','tmp_label'=>'监督检查','tmp_active'=>true])
@endsection
@section('content_form')
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane show active" id="supervision-tab" aria-labelledby="supervision-btn">
            <div class="col-12 mt-3" id="supervision">{!! $str !!}</div>
            @include('template.sidebar-btn',['tmp_class'=>'btn-copy','tmp_label'=>'复 制',])
        </div>
    </div>
    <script src="/admin/assets/js/pages/supervision.js"></script>
@endsection
