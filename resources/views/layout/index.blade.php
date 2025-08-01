@extends('layout.main')

@section('content')
    <!-- 开始 行 -->
    <div class="row flex-row">
        <div class="col-12">
            <!-- 开始 列表 -->
            <div class="widget has-shadow">
                <div class="widget-header bordered d-flex align-items-center">
                    <h2 class="page-title"></h2>
                    <ul class="nav nav-tabs">
                        @yield('content_btn')
                    </ul>
                </div>
                <div class="widget-body">
                    <div class="table-responsive ckp">
                        @yield("content_table")
                    </div>
                </div>
            </div>
            <!-- 结束 列表 -->
        </div>
    </div>
    <!-- 结束 行 -->
@endsection

@push('page-css')
    @stack('page-css-after-1')
@endpush
@push('page-js-after')
    @stack('page-js-after-1')
@endpush
