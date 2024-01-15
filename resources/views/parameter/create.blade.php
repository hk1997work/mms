@extends('layout.create')
@section('content_form')
    <div class="col-12 div-name">
        <div class="sidebar-heading mt-3 mb-2">参数名称</div>
        <input type="text" name="name" class="form-control">
    </div>
    <input type="hidden" name="pid" value="{{$id}}">
@endsection
