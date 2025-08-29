@extends('layout.create')
@section('content_form')
    <input type="hidden" name="type_id" value="{{$type_id}}">
    @include('template.input',['name'=>'instrument','label'=>'器具名称'])
    @include('template.input',['name'=>'model','label'=>'规格型号'])
    @include('template.input',['name'=>'limit','label'=>'测量范围'])
    @include('template.input',['name'=>'accuracy','label'=>'精确度'])
    @include('template.select',['name'=>'cycle_id','label'=>'检定周期','items'=>$cycles,'value'=>'id','field'=>'name'])
    @include('template.select',['name'=>'abc_id','label'=>'ABC类','items'=>$abcs,'value'=>'id','field'=>'name'])
    @include('template.select',['name'=>'plan_id','label'=>'检定计划','items'=>$plans,'value'=>'id','field'=>'name'])
    @include('template.select',['name'=>'vulnerable','label'=>'易损'])
    @include('template.input',['name'=>'requirement','label'=>'检定要求'])
@endsection
