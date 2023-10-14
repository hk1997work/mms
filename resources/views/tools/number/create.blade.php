@extends('layout.create')
@section('text_modal-title','增加编号')

@section('content_form')
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">出厂编号</label>
        <input type="text" name="number" id="number" class="form-control">
    </div>
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">管理状态</label>
        <select name="state_id" id="state_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($states->where('name','!=','在用')->where('name','!=','备用') as $state)
                <option value={{$state->id}}>{{$state->name}}</option>
            @endforeach
        </select>
    </div>
    <input type="hidden" name="factory_id" id="factory_id" value="{{$factory_id}}">
@endsection


