@extends("layout.main")

@section("content")
    <!-- 开始 行 -->
    <div class="row flex-row">
        <div class="col-xl-12">
            <!-- 开始 列表 -->
            <div class="widget has-shadow">
                <div class="widget-header bordered d-flex align-items-center">
                    <h2 class="page-title"></h2>
                </div>
                <div class="widget-body">
                    <form action="/cyclical" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-group row mb-3">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
                                <label class="form-control-label">类别</label>
                                <div><select name="type[]" class="selectpicker show-menu-arrow show-tick" multiple data-actions-box="true" data-selected-text-format="count"
                                             required>
                                        <option selected>质量</option>
                                        <option selected>安全</option>
                                    </select></div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
                                <label class="form-control-label">单位</label>
                                <div><select name="position[]" class="selectpicker show-menu-arrow show-tick" multiple data-actions-box="true" data-selected-text-format="count"
                                             required>
                                        @foreach($types->where('level',3)->where('pid',$types->where('level',3)->first()->pid)->where('sign',0) as $type)
                                            <option selected>{{$type->name}}</option>
                                        @endforeach
                                    </select></div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
                                <label class="form-control-label">选择年份</label>
                                <div><select name="year[]" class="selectpicker show-menu-arrow show-tick" multiple data-actions-box="true" data-selected-text-format="count"
                                             required>
                                        @foreach($years as $year)
                                            <option @if($year->year==date('Y')) selected @endif>{{$year->year}}</option>
                                        @endforeach
                                    </select></div>
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
@endpush
@push('page-js-after')
    <script src="/admin/assets/vendors/js/datepicker/moment.min.js"></script>
    <script src="/admin/assets/vendors/js/datepicker/daterangepicker.js"></script>

    <script src="/admin/assets/js/components/datepicker/datepicker.js"></script>
    <script src="/admin/assets/vendors/js/bootstrap-select/bootstrap-select.min.js"></script>
@endpush
