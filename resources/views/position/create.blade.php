@extends('layout.create')
@section('content_form')
    <div class="col-12 div-name">
        <div class="sidebar-heading mt-3 mb-2">岗位名称</div>
        <input type="text" name="name" class="form-control">
    </div>
    @if(isset($position)&&$position->level==4)
        <div class="col-12 div-code">
            <div class="sidebar-heading mt-3 mb-2">编号</div>
            <input type="text" name="code" class="form-control">
        </div>
    @endif
    <div class="col-12 div-sign">
        <div class="sidebar-heading mt-3 mb-2">状态</div>
        <select name="sign" class="custom-select form-control" required>
            <option value="0">有效</option>
            <option value="1">失效</option>
        </select>
    </div>
    <input type="hidden" name="pid" value="{{isset($position->id)?$position->id:0}}">
@endsection
