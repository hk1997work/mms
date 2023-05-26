@extends('layout.create')
@section('text_modal-title','增加岗位')

@section('content_form')
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">岗位名称</label>
        <input type="text" name="name" id="name" class="form-control">
    </div>
    @if(isset($position)&&$position->level==4)
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
            <label class="form-control-label">编号</label>
            <input type="text" name="code" id="code" class="form-control">
        </div>
    @endif
    <input type="hidden" name="pid" id="pid" value="{{isset($position->id)?$position->id:0}}">
@endsection


