@extends('layout.edit')
@section('text_modal-title','修改编号')

@section('content_form')
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">生产编号</label>
        <input type="text" name="number" id="number" class="form-control" value="{{$number->number}}">
    </div>
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
        <label class="form-control-label">管理状态</label>
        <select name="state_id" id="state_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($states as $state)
                <option value={{$state->id}} @if($state->id == $number->state_id) selected @endif>{{$state->name}}</option>
            @endforeach
        </select>
    </div>
    <input type="hidden" name="factory_id" id="factory_id" value="{{$number->factory_id}}">
@endsection


