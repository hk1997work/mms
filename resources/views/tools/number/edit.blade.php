@extends('layout.edit')
@section('content_form')
    <div class="col-12 div-number">
        <div class="sidebar-heading mt-3 mb-2">出厂编号</div>
        <input type="text" name="number" class="form-control" value="{{$number->number}}">
    </div>
    <div class="col-12 div-state_id">
        <div class="sidebar-heading mt-3 mb-2">管理状态</div>
        <select name="state_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($states as $state)
                <option value={{$state->id}} @if($state->id == $number->state_id) selected @endif>{{$state->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 div-remark">
        <div class="sidebar-heading mt-3 mb-2">备注</div>
        <input type="text" name="remark" class="form-control" value="{{$number->remark}}">
    </div>
@endsection
