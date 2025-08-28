@extends('layout.edit')
@section('content_form')
    <div class="col-12 mt-3">
        @foreach($roles as $role)
            <div class="mt-2">
                <input type="checkbox" class="btn-check" name="role[]" id="role{{$role->id}}" value="{{$role->id}}" @if($myRoles!=''&&$myRoles->contains($role)) checked @endif>
                <label class="btn btn-sm" for="role{{$role->id}}">{{$role->name}}</label>
            </div>
        @endforeach
    </div>
@endsection
