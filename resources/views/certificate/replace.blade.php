@extends('layout.edit')
@section('text_modal-title','更新证书')

@section('content_form')
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">岗位</label>
        <input type="text" name="position" id="position" class="form-control" value="{{$certificate->position}}-{{$certificate->code}}" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">序号</label>
        <input type="text" name="sn" id="sn" class="form-control" value="{{$certificate->sn}}" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">证书类型</label>
        <select name="category_id" id="category_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">器具名称</label>
        <select name="tool_id" id="tool_id" class="form-control selectpicker show-menu-arrow" data-live-search="true" onchange="load_info()">
            <option title="请选择..." value="" selected disabled>请选择...</option>
            @foreach($tools as $tool)
                <option title="{{$tool->instrument}}" value="{{$tool->id}}" @if($certificate->tool_id==$tool->id) selected @endif>{{$tool->instrument}}--{{$tool->model}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">生产厂家</label>
        <div class="input-group">
            <select name="factory_id" id="factory_id" class="custom-select form-control" onchange="load_numbers({{$certificate->number_id}})" disabled>
                <option value="" selected disabled>请选择...</option>
            </select>
            <span class="input-group-addon addon-primary" id="factory_id_btn" onclick="factory_id_click({{$certificate->number_id}})">增加</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">出厂编号</label>
        <div class="input-group">
            <select name="number_id" id="number_id" class="custom-select form-control" onchange="load_validity_date()" disabled>
                <option value="" selected disabled>请选择...</option>
            </select>
            <span class="input-group-addon addon-primary" id="number_id_btn" onclick="number_id_click({{$certificate->number_id}})">增加</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">证书名称</label>
        <input type="text" name="certificate_name" id="certificate_name" class="form-control">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">统一编号</label>
        <input type="text" name="certificate_no" id="certificate_no" class="form-control">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定部门</label>
        <select name="department_id" id="department_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($departments as $department)
                <option value="{{$department->id}}">{{$department->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定日期</label>
        <input type="text" name="verification_date" id="verification_date" class="form-control" onchange="load_validity_date()">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">有效期</label>
        <input type="text" name="validity_date" id="validity_date" class="form-control" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">规格型号</label>
        <input type="text" name="model" id="model" class="form-control" value="{{$certificate->model}}" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">测量范围</label>
        <input type="text" name="limit" id="limit" class="form-control" value="{{$certificate->limit}}" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">精确度</label>
        <input type="text" name="accuracy" id="accuracy" class="form-control" value="{{$certificate->accuracy}}" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定周期</label>
        <input type="text" name="cycle_id" id="cycle_id" class="form-control" value="{{$certificate->cycle}}" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">ABC</label>
        <input type="text" name="abc_id" id="abc_id" class="form-control" value="{{$certificate->abc}}" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">启用时间</label>
        <input type="text" name="start" id="start" class="form-control" value="{{$certificate->start}}" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定次数</label>
        <input type="text" name="times" id="times" class="form-control" value="{{$certificate->times+1}}" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定费用</label>
        <input type="text" name="money" id="money" class="form-control" value="{{$certificate->money}}">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定标准</label>
        <select name="standard_id[]" id="standard_id" class="form-control selectpicker show-menu-arrow" data-live-search="true" multiple>
            @foreach($standards as $standard)
                <option value="{{$standard->id}}">{{$standard->name1}}-{{$standard->name2}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">备注</label>
        <input type="text" name="remark" id="remark" class="form-control" value="{{$certificate->remark}}">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">更换原因</label>
        <select name="cause" id="cause" class="custom-select form-control" required>
            <option value="0">更换</option>
            <option value="1">损坏</option>
        </select>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">送检计划</label>
        <input type="text" name="plan_id" id="plan_id" class="form-control" value="{{$certificate->plan}}" readonly>
    </div>
    <input type="hidden" name="position_id" id="position_id" value="{{$certificate->position_id}}">
    <input type="hidden" name="number" id="number" value="{{$certificate->number_id}}">
    </div>
    <div class="form-group row mb-3">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
            <button type="button" id="btn_certificate" class="btn btn-secondary btn-sm ripple">上传计量证书</button>
            <input type="file" name="file_certificate" id="file_certificate" accept="application/pdf" style="display: none">
            <label class="form-control-label" id="text_certificate"></label>
        </div>
    </div>
    <div>
        @if($spares->count()!=0)
            <table class="table table-hover mb-0">
                <thead>
                <tr>
                    <th class="d-none d-lg-table-cell">序号</th>
                    <th class="d-none d-lg-table-cell">器具名称</th>
                    <th class="d-none d-lg-table-cell">规格型号</th>
                    <th class="d-none d-lg-table-cell">出厂编号</th>
                    <th class="d-none d-lg-table-cell">有效期</th>
                    <th class="d-none d-lg-table-cell">检定部门</th>
                    <th class="d-none d-lg-table-cell">备注</th>
                    <th class="d-lg-none">名称</th>
                    <th class="d-lg-none">信息</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($spares as $spare)
                    <tr @if($spare->valid == 1 && $spare->validity_date < date("Y-m-d")) class="text-danger" @endif>
                        <td class="d-none d-lg-table-cell">{{$spare->order}}</td>
                        <td class="d-none d-lg-table-cell">{{$spare->instrument}}</td>
                        <td class="d-none d-lg-table-cell">{{$spare->model}}</td>
                        <td class="d-none d-lg-table-cell">{{$spare->number}}</td>
                        <td class="d-none d-lg-table-cell">{{$spare->validity_date}}</td>
                        <td class="d-none d-lg-table-cell">{{$spare->department}}</td>
                        <td class="d-none d-lg-table-cell">{{$spare->remark}}</td>
                        <td class="d-lg-none">{{$spare->order}}<br>{{$spare->instrument}}<br>{{$spare->model}}</td>
                        <td class="d-lg-none">{{$spare->number}}<br>{{$spare->validity_date}}<br>{{$spare->remark}}</td>
                        <td class="td-actions">
                            <btn class="btn btn-outline-secondary btn-sm ripple" onclick="modal_displace({{$certificate->id}},{{$spare->id}},0)">更换</btn>
                            <btn class="btn btn-outline-secondary btn-sm ripple" onclick="modal_displace({{$certificate->id}},{{$spare->id}},1)">损坏</btn>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        @endsection
        <link rel="stylesheet" href="/admin/assets/css/bootstrap-select/bootstrap-select.min.css">

        <script src="/admin/assets/vendors/js/datepicker/moment.min.js"></script>
        <script src="/admin/assets/vendors/js/datepicker/daterangepicker.js"></script>
        <script src="/admin/assets/vendors/js/bootstrap-select/bootstrap-select.min.js"></script>

        <script src="/admin/assets/js/components/datepicker/datepicker.js"></script>
        <script src="/admin/assets/js/pages/certificate.js"></script>
        <script>
            $(document).ready(function () {
                file()
            })
            $('#tool_id').selectpicker();
            $('#standard_id').selectpicker();

            //替换操作
            function modal_displace(old_id, new_id, cause) {
                $.ajax({
                    url: "/displace/" + old_id + "/" + new_id + "/" + cause,
                    type: "PUT",
                    data: {"_token": "{{csrf_token()}}"},
                    success: function (result) {
                        if (result == true) {
                            document.location.reload();
                        } else {
                            notifications('替换失败');
                        }
                    }, error: function (xhr) {
                        if (xhr.status == 401) {
                            document.location.reload();
                        } else {
                            notifications('替换失败')
                        }
                    }
                });
            }
        </script>
