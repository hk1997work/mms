@extends('layout.edit')
@section('content_form')
    <div class="col-12">
        <div class="sidebar-heading mt-3 mb-2">序号</div>
        <input type="text" name="sn" id="sn" class="form-control" value="{{$certificate->sn}}">
    </div>
@endsection
