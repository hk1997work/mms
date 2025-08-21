@extends('layout.edit')
@section('content_form')
    <div class="col-12 div-name">
        <div class="sidebar-heading mt-3 mb-2">岗位名称</div>
        <input type="text" name="name" class="form-control" value="{{$position->name}}">
    </div>
    @if($position->level==4)
        <div class="col-12 div-code">
            <div class="sidebar-heading mt-3 mb-2">编号</div>
            <input type="text" name="code" class="form-control" value="{{$position->code}}">
        </div>
    @endif
    <div class="col-12 div-sign">
        <div class="sidebar-heading mt-3 mb-2">状态</div>
        <select name="sign" class="custom-select form-control">
            <option value="1" @if($position->sign==1)selected @endif>有效</option>
            <option value="0" @if($position->sign==0)selected @endif>失效</option>
        </select>
    </div>
@endsection
