@extends('layout.edit')
@section('text_modal-title','修改量具')

@section('content_form')
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">器具名称</label>
        <input type="text" name="instrument" id="instrument" class="form-control" value="{{$tool->instrument}}">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">规格型号</label>
        <input type="text" name="model" id="model" class="form-control" value="{{$tool->model}}">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">测量范围</label>
        <input type="text" name="limit" id="limit" class="form-control" value="{{$tool->limit}}">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">精确度</label>
        <input type="text" name="accuracy" id="accuracy" class="form-control" value="{{$tool->accuracy}}">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定周期</label>
        <select name="cycle_id" id="cycle_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($cycles as $cycle)
                <option value="{{$cycle->id}}" @if($cycle->id == $tool->cycle_id) selected @endif>{{$cycle->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">ABC类</label>
        <select name="abc_id" id="abc_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($abcs as $abc)
                <option value="{{$abc->id}}" @if($abc->id == $tool->abc_id) selected @endif>{{$abc->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定计划</label>
        <select name="plan_id" id="plan_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($plans as $plan)
                <option value="{{$plan->id}}" @if($plan->id == $tool->plan_id) selected @endif>{{$plan->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">检定要求</label>
        <input type="text" name="requirement" id="requirement" class="form-control" value="{{$tool->requirement}}">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">易损量具</label>
        <select name="vulnerable" id="vulnerable" class="custom-select form-control">
            <option value="" disabled>请选择...</option>
            <option value="1" @if($tool->vulnerable==1) selected @endif>是</option>
            <option value="0" @if($tool->vulnerable==0) selected @endif>否</option>
        </select>
    </div>
@endsection
