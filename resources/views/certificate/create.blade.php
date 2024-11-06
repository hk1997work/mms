@extends('layout.create')
@section('content_form')
    <div class="row">
        <div class="col-6 div-position_id">
            <div class="sidebar-heading mt-3 mb-2">岗位</div>
            <select name="position_id" class="custom-select form-control">
                <option value="" selected disabled>请选择...</option>
                @foreach($positions->where('level',4) as $p1)
                    <optgroup label="{{$p1->name1}}">
                        @foreach($positions->where('pid',$p1->id) as $p2)
                            <option value="{{$p2->id}}">{{$p2->name1}}-{{$p2->code}}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="col-6 div-sn">
            <div class="sidebar-heading mt-3 mb-2">序号</div>
            <input type="text" name="sn" class="form-control" readonly>
        </div>
        <div class="col-6 div-tool_id">
            <div class="sidebar-heading mt-3 mb-2">器具名称</div>
            <select name="tool_id" class="custom-select form-control" data-live-search="true">
                <option title="请选择..." value="" selected disabled>请选择...</option>
                @foreach($tools as $tool)
                    <option title="{{$tool->instrument}}" value="{{$tool->id}}">{{$tool->instrument}}--{{$tool->model}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 div-model">
            <div class="sidebar-heading mt-3 mb-2">规格型号</div>
            <input type="text" name="model" class="form-control" readonly>
        </div>
        <div class="col-6 div-factory_id">
            <div class="sidebar-heading mt-3 mb-2">生产厂家</div>
            <div class="input-group">
                <select name="factory_id" class="custom-select form-control" disabled>
                    <option value="" selected disabled>请选择...</option>
                </select>
                <span class="input-group-addon addon-primary btn-add factory_add" data-pos='right' data-menu='factory' data-cb="tool_id" hidden>增加</span>
            </div>
        </div>
        <div class="col-6 div-limit">
            <div class="sidebar-heading mt-3 mb-2">测量范围</div>
            <input type="text" name="limit" class="form-control" readonly>
        </div>
        <div class="col-6 div-number_id">
            <div class="sidebar-heading mt-3 mb-2">出厂编号</div>
            <div class="input-group">
                <select name="number_id" class="custom-select form-control" disabled>
                    <option value="" selected disabled>请选择...</option>
                </select>
                <span class="input-group-addon addon-primary btn-add number_add" data-pos='right' data-menu='number' data-cb="factory_id" hidden>增加</span>
            </div>
        </div>
        <div class="col-6 div-accuracy">
            <div class="sidebar-heading mt-3 mb-2">精确度</div>
            <input type="text" name="accuracy" class="form-control" readonly>
        </div>
        <div class="col-6 div-department_id">
            <div class="sidebar-heading mt-3 mb-2">检定部门</div>
            <select name="department_id" class="custom-select form-control">
                <option value="" selected disabled>请选择...</option>
                @foreach($departments as $department)
                    <option value="{{$department->id}}">{{$department->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 div-cycle_id">
            <div class="sidebar-heading mt-3 mb-2">检定周期</div>
            <input type="text" name="cycle_id" class="form-control" readonly>
        </div>

        <div class="col-6 div-category_id">
            <div class="sidebar-heading mt-3 mb-2">证书类型</div>
            <select name="category_id" class="custom-select form-control">
                <option value="" selected disabled>请选择...</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 div-abc_id">
            <div class="sidebar-heading mt-3 mb-2">ABC</div>
            <input type="text" name="abc_id" class="form-control" readonly>
        </div>
        <div class="col-6 div-verification_date">
            <div class="sidebar-heading mt-3 mb-2">检定日期</div>
            <input type="text" name="verification_date" class="form-control">
        </div>
        <div class="col-6 div-validity_date">
            <div class="sidebar-heading mt-3 mb-2">有效期</div>
            <input type="text" name="validity_date" class="form-control" readonly>
        </div>
        <div class="col-6 div-certificate_no">
            <div class="sidebar-heading mt-3 mb-2">统一编号</div>
            <input type="text" name="certificate_no" class="form-control">
        </div>
        <div class="col-6 div-start">
            <div class="sidebar-heading mt-3 mb-2">启用时间</div>
            <input type="text" name="start" class="form-control" readonly>
        </div>
        <div class="col-6 div-standard_id">
            <div class="sidebar-heading mt-3 mb-2">检定标准</div>
            <select name="standard_id[]" class="form-control" data-live-search="true" multiple>
                @foreach($standards as $standard)
                    <option value="{{$standard->id}}">{{$standard->name1}}-{{Str::limit($standard->name2,35)}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 div-times">
            <div class="sidebar-heading mt-3 mb-2">检定次数</div>
            <input type="text" name="times" class="form-control" readonly>
        </div>
        <div class="col-6 div-certificate_name">
            <div class="sidebar-heading mt-3 mb-2">证书名称</div>
            <input type="text" name="certificate_name" class="form-control">
        </div>
        <div class="col-6 div-plan_id">
            <div class="sidebar-heading mt-3 mb-2">检定计划</div>
            <input type="text" name="plan_id" class="form-control" readonly>
        </div>
        <div class="col-6 div-remark">
            <div class="sidebar-heading mt-3 mb-2">备注</div>
            <input type="text" name="remark" class="form-control">
        </div>
        <div class="col-6">
            <div class="sidebar-heading mt-3 mb-2">上传证书</div>
            <input type="file" class="btn btn-secondary btn-square btn-sm" name="file_certificate" accept="application/pdf">
        </div>
    </div>
    <script>
        $('[name="tool_id"],[name="standard_id[]"]').selectpicker();
        $('[name="verification_date"]').daterangepicker({
            singleDatePicker: true,
            container: '[name="verification_date"]',
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $('[name="verification_date"]').on('change', function () {
            $('[name="start"]').val($('[name="verification_date"]').val().substring(0, 4))
        })
    </script>
@endsection
