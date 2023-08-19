@extends('layout.edit')
@section('text_modal-title',"修改序号：$certificate->sn")

@section('content_form')
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
        <label class="form-control-label">序号</label>
        <input type="text" name="sn" id="sn" class="form-control" value="{{$certificate->sn}}">
    </div>
@endsection
