@extends('layout.edit')
@section('content_form')
    @include('template.checklist',['tmp_name'=>'role','tmp_items'=>$roles,'tmp_field'=>'name','tmp_checked'=>$myRoles])
@endsection
