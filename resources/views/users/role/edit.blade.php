@extends('layout.edit')
@section('text_modal-title',"修改角色：$role->name")

@section('content_form')
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
        <label class="form-control-label">角色名称</label>
        <input type="text" name="name" id="name" class="form-control" value="{{$role->name}}">
    </div>
@endsection
