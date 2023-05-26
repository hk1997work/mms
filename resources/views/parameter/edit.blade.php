@extends('layout.edit')
@section('text_modal-title','修改参数')

@section('content_form')
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
        <label class="form-control-label">参数名称</label>
        <input type="text" name="name" id="name" class="form-control" value="{{$parameter->name}}">
    </div>
@endsection


