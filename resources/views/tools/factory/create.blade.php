@extends('layout.create')
@section('content_form')
    <div class="col-12 div-factory">
        <div class="sidebar-heading mt-3 mb-2">生产厂家</div>
        <input type="text" name="factory" class="form-control" value="{{$name}}">
    </div>
    <div class="col-12 div-fullname">
        <div class="sidebar-heading mt-3 mb-2">厂家全称</div>
        <input type="text" name="fullname" class="form-control" value="{{$name}}">
    </div>
    <input type="hidden" name="tool_id" value="{{$tool_id}}">
@endsection
