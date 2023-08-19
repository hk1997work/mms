@extends('layout.edit')
@section('text_modal-title',"修改岗位：$position->name")

@section('content_form')
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">岗位名称</label>
        <input type="text" name="name" id="name" class="form-control" value="{{$position->name}}">
    </div>
    @if($position->level==5)
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
            <label class="form-control-label">编号</label>
            <input type="text" name="code" id="code" class="form-control" value="{{$position->code}}">
        </div>
    @endif
    <input type="hidden" name="pid" id="pid" value="{{$position->pid}}">
@endsection


