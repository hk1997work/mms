@extends('layout.edit')
@section('content_form')
    @include('template.select-group',['tmp_name'=>'position_id','tmp_label'=>'岗位','tmp_items'=>$positions,'tmp_selected'=>$certificate->position_id,'tmp_validate'=>'id','tmp_value'=>'id','tmp_field1'=>'name','tmp_field2'=>'code'])
    @include('template.input',['tmp_name'=>'sn','tmp_label'=>'序号','tmp_value'=>$certificate->sn])
@endsection
