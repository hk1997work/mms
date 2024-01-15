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
    @if($permission->level==1)
        <div class="col-12 div-icon">
            <div class="sidebar-heading mt-3 mb-2">图标</div>
            <input type="text" name="icon" class="form-control" value="{{$permission->icon}}">
        </div>
    @endif
@endsection
