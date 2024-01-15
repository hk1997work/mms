@extends('layout.edit')
@section('content_form')
    <div class="col-12 div-instrument">
        <div class="sidebar-heading mt-3 mb-2">器具名称</div>
        <input type="text" name="instrument" class="form-control" value="{{$tool->instrument}}">
    </div>
    <div class="col-12 div-model">
        <div class="sidebar-heading mt-3 mb-2">规格型号</div>
        <input type="text" name="model" class="form-control" value="{{$tool->model}}">
    </div>
    <div class="col-12 div-limit">
        <div class="sidebar-heading mt-3 mb-2">测量范围</div>
        <input type="text" name="limit" class="form-control" value="{{$tool->limit}}">
    </div>
    <div class="col-12 div-accuracy">
        <div class="sidebar-heading mt-3 mb-2">精确度</div>
        <input type="text" name="accuracy" class="form-control" value="{{$tool->accuracy}}">
    </div>
    <div class="col-12 div-cycle_id">
        <div class="sidebar-heading mt-3 mb-2">检定周期</div>
        <select name="cycle_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($cycles as $cycle)
                <option value="{{$cycle->id}}" @if($cycle->id == $tool->cycle_id) selected @endif>{{$cycle->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 div-abc_id">
        <div class="sidebar-heading mt-3 mb-2">ABC类</div>
        <select name="abc_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($abcs as $abc)
                <option value="{{$abc->id}}" @if($abc->id == $tool->abc_id) selected @endif>{{$abc->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 div-plan_id">
        <div class="sidebar-heading mt-3 mb-2">检定计划</div>
        <select name="plan_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($plans as $plan)
                <option value="{{$plan->id}}" @if($plan->id == $tool->plan_id) selected @endif>{{$plan->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 div-vulnerable">
        <div class="sidebar-heading mt-3 mb-2">易损</div>
        <select name="vulnerable" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            <option value="1" @if($tool->vulnerable==1) selected @endif>是</option>
            <option value="0" @if($tool->vulnerable==0) selected @endif>否</option>
        </select>
    </div>
    <div class="col-12 div-requirement">
        <div class="sidebar-heading mt-3 mb-2">检定要求</div>
        <input type="text" name="requirement" class="form-control" value="{{$tool->requirement}}">
    </div>
@endsection
