@extends('layout.create')
@section('text_modal-title','增加权限')

@section('content_form')
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
        <label class="form-control-label">权限名称</label>
        <input type="text" name="name" id="name" class="form-control">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
        <label class="form-control-label">权限描述</label>
        <input type="text" name="description" id="description" class="form-control">
    </div>
    @if($id==0)
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
            <label class="form-control-label">图标</label>
            <input type="text" name="icon" id="icon" class="form-control">
        </div>
    @endif
    <input type="hidden" name="pid" id="pid" value="{{$id}}">
@endsection



