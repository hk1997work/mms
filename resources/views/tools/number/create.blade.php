@extends('layout.create')
@section('content_form')
    <div class="col-12 div-number">
        <div class="sidebar-heading mt-3 mb-2">出厂编号</div>
        <input type="text" name="number" class="form-control">
    </div>
    <div class="col-12 div-state_id">
        <div class="sidebar-heading mt-3 mb-2">管理状态</div>
        <select name="state_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($states->where('name','!=','在用')->where('name','!=','备用') as $state)
                <option value={{$state->id}}>{{$state->name}}</option>
            @endforeach
        </select>
    </div>
    <input type="hidden" name="factory_id" value="{{$factory_id}}">
@endsection

