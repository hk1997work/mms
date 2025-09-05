@extends('layout.edit')
@section('content_form')
    @include('template.select-group',['name'=>'position_id','label'=>'岗位','selected'=>$certificate->position_id,'items'=>$positions,'value'=>'id','validate'=>'id','field1'=>'name','field2'=>'code'])
    @include('template.input',['name'=>'sn','label'=>'序号','value'=>$certificate->sn])
@endsection
