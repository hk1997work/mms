@extends('layout.edit')
@section('content_form')
    @include('template.checklist',['tmp_items'=>$roles,'tmp_name'=>'role','tmp_field'=>'name','tmp_checked'=>$myRoles])
@endsection
