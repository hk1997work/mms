@extends("layout.main")
@section("content")
    <!-- 开始 行 -->
    <div class="row flex-row">
        <div class="col-xl-12">
            <!-- 开始 列表 -->
            <div class="widget has-shadow">
                <div class="widget-header bordered d-flex align-items-center">
                    <h2 class="page-title"></h2>
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link btn-add" data-menu="export" data-pos="left" href="#">设置</a></li>
                    </ul>
                </div>
                <div class="widget-body">
                    <form action="/export/1" method="post" enctype="multipart/form-data">
                        {{method_field("put")}}
                        {{csrf_field()}}
                        <div class="form-group row mb-3">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
                                <label class="form-control-label">文件类型</label>
                                <div>
                                    <select name="contents[]" class="selectpicker show-menu-arrow show-tick" multiple data-actions-box="true" data-selected-text-format="count">
                                        <option selected>台账</option>
                                        <option>核对台账</option>
                                        <option>标准台账</option>
                                        <option>计量证书</option>
                                        <option>监理资料</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
                                <label class="form-control-label">计量类别</label>
                                <div>
                                    <select name="type[]" class="selectpicker show-menu-arrow show-tick" multiple data-actions-box="true" data-selected-text-format="count">
                                        @foreach($types as $type)
                                            <option selected>{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="styled-checkbox mt-3">
                                    <input type="checkbox" name="check_type" id="check_type">
                                    <label for="check_type">导出多个文件</label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
                                <label class="form-control-label">单位</label>
                                <div>
                                    <select name="position[]" class="selectpicker show-menu-arrow show-tick" multiple data-actions-box="true" data-selected-text-format="count">
                                        @foreach($levels as $level)
                                            <option selected>{{$level->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="styled-checkbox mt-3">
                                    <input type="checkbox" name="check_position" id="check_position">
                                    <label for="check_position">导出多个文件</label>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
                                <label class="form-control-label">选择日期</label>
                                <input type="text" class="form-control" name="daterange" id="daterange" style="width: 218px" disabled>
                                <div class="styled-checkbox mt-3">
                                    <input type="checkbox" name="check_daterange" id="check_daterange">
                                    <label for="check_daterange">选择日期范围</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary ripple pull-right mb-4">下 载</button>
                    </form>
                </div>
            </div>
            <!-- 结束 列表 -->
        </div>
    </div>
    <!-- 结束 行 -->
@endsection
@push('page-css')
    <link rel="stylesheet" href="/admin/assets/css/bootstrap-select/bootstrap-select.min.css">
    <link rel="stylesheet" href="/admin/assets/css/datatables/datatables.min.css">
    <link rel="stylesheet" href="/admin/assets/css/datatables/fixedColumns.dataTables.min.css">
@endpush
@push('page-js-after')
    <script src="/admin/assets/vendors/js/datepicker/daterangepicker.js"></script>
    <script src="/admin/assets/vendors/js/datatables/datatables.min.js"></script>
    <script src="/admin/assets/vendors/js/datatables/dataTables.fixedColumns.min.js"></script>
    <script src="/admin/assets/js/components/tables/tables.js"></script>
    <script src="/admin/assets/js/components/datepicker/datepicker.js"></script>
    <script src="/admin/assets/vendors/js/bootstrap-select/bootstrap-select.min.js"></script>

    <script>
        $('#check_daterange').change(function () {
            if ($('#check_daterange').is(':checked')) {
                $("#daterange").prop("disabled", false);
                $("#check_valid").prop("checked", true);
                $("#check_valid").prop("disabled", true);
            } else {
                $('#daterange').prop("disabled", true);
                $("#check_valid").prop("checked", false);
                $("#check_valid").prop("disabled", false);
            }
        })
    </script>
@endpush
