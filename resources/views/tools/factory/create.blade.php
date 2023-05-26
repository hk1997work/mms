@extends('layout.create')
@section('text_modal-title','增加厂家')

@section('content_form')
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">生产厂家</label>
        <input type="text" name="factory" id="factory" class="form-control">
    </div>
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">厂家全称</label>
        <input type="text" name="fullname" id="fullname" class="form-control">
    </div>
    <input type="hidden" name="tool_id" id="tool_id" value="{{$tool_id}}">
@endsection


