@extends('layout.submit')
@section('content_form')
    <div class="col-12 div-row">
        <div class="sidebar-heading mt-3 mb-2">起始行(1-10)</div>
        <input type="text" name="row" class="form-control" value="1">
    </div>
    <div class="col-12 div-column">
        <div class="sidebar-heading mt-3 mb-2">起始列(1-5)</div>
        <input type="text" name="column" class="form-control" value="1">
    </div>
    <div class="col-12">
        <input type="checkbox" name="check_position" id="check_position">
        <label class="sidebar-heading mt-3 mb-2" for="check_position">打印岗位信息</label>
    </div>
@endsection
