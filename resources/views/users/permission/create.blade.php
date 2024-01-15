@extends('layout.create')
@section('content_form')
    <div class="col-12 div-name">
        <div class="sidebar-heading mt-3 mb-2">权限名称</div>
        <input type="text" name="name" class="form-control">
    </div>
    <div class="col-12 div-description">
        <div class="sidebar-heading mt-3 mb-2">权限描述</div>
        <input type="text" name="description" class="form-control">
    </div>
    @if($id==0)
        <div class="col-12 div-icon">
            <div class="sidebar-heading mt-3 mb-2">图标</div>
            <input type="text" name="icon" class="form-control">
        </div>
    @endif
    <input type="hidden" name="pid" value="{{$id}}">
@endsection
