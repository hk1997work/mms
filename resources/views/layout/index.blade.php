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
    <div id="modal-delete" class="modal fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">确认删除?</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary ripple submit-delete" data-url="">确 定</button>
                    <button class="btn btn-outline-secondary ripple" data-dismiss="modal">取 消</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-css')
    <link rel="stylesheet" href="/admin/assets/css/datatables/datatables.min.css">
    <link rel="stylesheet" href="/admin/assets/css/datatables/fixedColumns.dataTables.min.css">

@endpush
@push('page-js-after')
    <script src="/admin/assets/vendors/js/datatables/datatables.min.js"></script>
    <script src="/admin/assets/vendors/js/datatables/dataTables.fixedColumns.min.js"></script>
    <script src="/admin/assets/js/components/tables/tables.js"></script>
    @stack('page-js-after-1')
@endpush
