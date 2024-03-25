@extends('layout.create')
@section('content_form')
    <div class="col-12 div-number">
        <div class="sidebar-heading mt-3 mb-2">出厂编号</div>
        <input type="text" name="number" class="form-control" value="{{$name}}">
    </div>
    <div class="col-12 div-state_id">
        <div class="sidebar-heading mt-3 mb-2">管理状态</div>
        <select name="state_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($states->where('name','!=','在用') as $state)
                <option value='{{$state->id}}' @if(isset($name)&&$state->name=='待检') selected @endif>{{$state->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 div-remark">
        <div class="sidebar-heading mt-3 mb-2">备注</div>
        <input type="text" name="remark" class="form-control">
    </div>
    <input type="hidden" name="factory_id" value="{{$factory_id}}">
@endsection

