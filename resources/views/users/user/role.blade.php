@extends('layout.edit')
@section('text_modal-title','用户权限')

@section('content_form')
    @foreach($roles as $role)
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3 styled-checkbox">
            <input type="checkbox" name="roles[]" id="{{$role->id}}" value="{{$role->id}}" @if($myRoles->contains($role)) checked @endif>
            <label for="{{$role->id}}">{{$role->name}}</label>
        </div>
    @endforeach
@endsection
