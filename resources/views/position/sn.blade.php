@extends('layout.edit')
@section('content_form')
    @include('template.select',['name'=>'position_id','label'=>'岗位','selected'=>$certificate->position_id,'items'=>$positions,'value'=>'id','validate'=>'id','field'=>'name1'])
    @include('template.input',['name'=>'sn','label'=>'序号','value'=>$certificate->sn])
@endsection
