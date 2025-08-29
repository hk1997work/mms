@extends('layout.edit')
@section('content_form')
    @include('template.checklist',['items'=>$roles,'name'=>'role','field'=>'name','checked'=>$myRoles])
@endsection
