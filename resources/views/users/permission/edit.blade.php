@extends('layout.edit')
@section('content_form')
    <div class="col-12 div-name">
        <div class="sidebar-heading mt-3 mb-2">权限名称</div>
        <input type="text" name="name" class="form-control" value="{{$permission->name}}">
    </div>
    <div class="col-12 div-description">
        <div class="sidebar-heading mt-3 mb-2">权限描述</div>
        <input type="text" name="description" class="form-control" value="{{$permission->description}}">
    </div>
@endsection
