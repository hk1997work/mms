@extends('layout.edit')
@section('content_form')
    <div class="col-12">
        @foreach($roles as $role)
            <div class="styled-checkbox">
                <input type="checkbox" name="role[]" id="role{{$role->id}}" value="{{$role->id}}" @if($myRoles!=''&&$myRoles->contains($role)) checked @endif>
                <label class="sidebar-heading" for="role{{$role->id}}">{{$role->name}}</label>
            </div>
        @endforeach
    </div>
@endsection
