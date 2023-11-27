@extends('layout.main')

@section('content')
    <!-- 开始 行 -->
    <div class="row flex-row">
        <div class="col-xl-12">
            <!-- 开始 列表 -->
            <div class="widget has-shadow">
                <div class="widget-header bordered d-flex align-items-center" style="height: 60px">
                    <h2 class="page-title"></h2>
                    <ul class="nav nav-tabs">
                        @yield('content_btn')
                    </ul>
                </div>
                <div class="widget-body">
                    <div class="table-responsive">
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
    <link rel="stylesheet" href="/admin/assets/css/datatables/datatables.min.css">
@endpush
@push('page-js-before')
    <script src="/admin/assets/vendors/js/datatables/datatables.min.js"></script>
    <script src="/admin/assets/js/components/tables/tables.js"></script>
@endpush
@push('page-js-after')
    @stack('page-js-after-1')
@endpush
