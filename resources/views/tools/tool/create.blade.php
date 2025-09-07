@extends('layout.create')
@section('content_form')
    <input type="hidden" name="type_id" value="{{$type_id}}">
    @include('template.input',['tmp_name'=>'instrument','tmp_label'=>'器具名称'])
    @include('template.input',['tmp_name'=>'model','tmp_label'=>'规格型号'])
    @include('template.input',['tmp_name'=>'limit','tmp_label'=>'测量范围'])
    @include('template.input',['tmp_name'=>'accuracy','tmp_label'=>'精确度'])
    @include('template.select',['tmp_name'=>'cycle_id','tmp_label'=>'检定周期','tmp_items'=>$cycles,'tmp_value'=>'id','tmp_field'=>'name'])
    @include('template.select',['tmp_name'=>'abc_id','tmp_label'=>'ABC类','tmp_items'=>$abcs,'tmp_value'=>'id','tmp_field'=>'name'])
    @include('template.select',['tmp_name'=>'plan_id','tmp_label'=>'检定计划','tmp_items'=>$plans,'tmp_value'=>'id','tmp_field'=>'name'])
    @include('template.select',['tmp_name'=>'vulnerable','tmp_label'=>'易损'])
    @include('template.input',['tmp_name'=>'requirement','tmp_label'=>'检定要求'])
@endsection
