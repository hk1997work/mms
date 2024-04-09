@extends('layout.edit')
@section('content_form')
    <div class="col-12">
        <div class="sidebar-heading mt-3 mb-2">岗位</div>
        <select name="position_id" class="custom-select form-control">
            <option value="" selected disabled>请选择...</option>
            @foreach($positions as $position)
                <option value="{{$position->id}}" @if($position->id == $certificate->position_id) selected @endif>{{$position->name1}}-{{$position->code}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <div class="sidebar-heading mt-3 mb-2">序号</div>
        <input type="text" name="sn" class="form-control" value="{{$certificate->sn}}">
    </div>
@endsection
