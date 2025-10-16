@extends('layout.show')
@section('content_title')
    <li class="nav-item">
        <button class="nav-link active" data-toggle="tab" data-target="#supervision-tab" id="supervision-btn">监督检查</button>
    </li>
@endsection
@section('content_form')
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane show active" id="supervision-tab" aria-labelledby="supervision-btn">
            <div class="col-12 mt-3" id="supervision">{!! $str !!}</div>
            <div class="position-fixed bottom-0 end-0 p-3">
                <button class="btn btn-outline-primary btn-copy sidebar-url">复 制</button>
                <button class="btn btn-outline-secondary sidebar-close">返 回</button>
            </div>
        </div>
    </div>
    <script src="/admin/assets/js/pages/supervision.js"></script>
@endsection
