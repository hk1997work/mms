@extends('layout.edit')
@section('text_modal-title',"确认屏蔽?")

@section('content_form')
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
        <label class="form-control-label">备注</label>
        <input type="text" name="remark" id="remark" class="form-control">
        <input type="hidden" name="str" id="str" value="{{$str}}">
    </div>
@endsection
