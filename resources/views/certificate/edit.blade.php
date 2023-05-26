@extends('layout.edit')
@section('text_modal-title','修改证书')

@section('content_form')
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">岗位</label>
        <input type="text" name="position" id="position" class="form-control" value="{{$certificate->position}}-{{$certificate->code}}" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">序号</label>
        <input type="text" name="sn" id="sn" class="form-control" value="{{$certificate->sn}}">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">证书类型</label>
        <select name="category_id" id="category_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($categories as $category)
                <option value="{{$category->id}}" @if($certificate->category_id==$category->id) selected @endif>{{$category->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12c mb-3">
        <label class="form-control-label">器具名称</label>
        <input type="text" name="tool" id="tool" class="form-control" value="{{$certificate->instrument}}" readonly>
        <input type="hidden" name="tool_id" id="tool_id" value="{{$certificate->tool_id}}">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">生产厂家</label>
        <input type="text" name="factory_id" id="factory_id" class="form-control" value="{{$certificate->factory}}" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">出厂编号</label>
        <input type="text" name="number_id" id="number_id" class="form-control" value="{{$certificate->number}}" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">统一编号</label>
        <input type="text" name="certificate_no" id="certificate_no" class="form-control" value="{{$certificate->certificate_no}}">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定部门</label>
        <select name="department_id" id="department_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($departments as $department)
                <option value="{{$department->id}}" @if($certificate->department_id==$department->id) selected @endif>{{$department->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定日期</label>
        <input type="text" name="verification_date" id="verification_date" class="form-control" onchange="load_validity_date()">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">有效期</label>
        <input type="text" name="validity_date" id="validity_date" class="form-control" value="{{$certificate->validity_date}}" readonly>
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
        <input type="text" name="start" id="start" class="form-control" value="{{$certificate->start}}">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定次数</label>
        <input type="text" name="times" id="times" class="form-control" value="{{$certificate->times}}">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">送检计划</label>
        <input type="text" name="plan_id" id="plan_id" class="form-control" value="{{$certificate->plan}}" readonly>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定费用</label>
        <input type="text" name="money" id="money" class="form-control" value="{{$certificate->money}}">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定标准</label>
        <select name="standard_id[]" id="standard_id" class="form-control selectpicker show-menu-arrow" data-live-search="true" multiple>
            @foreach($standards as $standard)
                @foreach($standard->toChildrens as $s)
                    <option value="{{$s->id}}" @if(in_array($s->id,$certificate->standard_id)) selected @endif>{{$standard->name}}-{{$s->name}}</option>
                @endforeach
            @endforeach
        </select>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">备注</label>
        <input type="text" name="remark" id="remark" class="form-control" value="{{$certificate->remark}}">
    </div>
    </div>
    <div class="form-group row mb-3">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
            <button type="button" id="btn_certificate" class="btn btn-secondary btn-sm ripple">上传计量证书</button>
            <input type="file" name="file_certificate" id="file_certificate" accept="application/pdf" style="display: none">
            <label class="form-control-label" id="text_certificate"></label>
        </div>
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
            $("#verification_date").val('{{$certificate->verification_date}}');
            $('#standard_id').selectpicker();
        </script>
