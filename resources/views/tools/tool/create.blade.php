@extends('layout.create')
@section('content_form')
    <input type="hidden" name="type_id" value="{{$type_id}}">
    @include('layout.input',['name'=>'instrument','label'=>'器具名称','type'=>'text','value'=>'','state'=>''])
    @include('layout.input',['name'=>'model','label'=>'规格型号','type'=>'text','value'=>'','state'=>''])
    @include('layout.input',['name'=>'limit','label'=>'测量范围','type'=>'text','value'=>'','state'=>''])
    @include('layout.input',['name'=>'accuracy','label'=>'精确度','type'=>'text','value'=>'','state'=>''])
    @include('layout.select',['name'=>'cycle_id','label'=>'检定周期','selected'=>null,'items'=>$cycles,'id'=>'id','field'=>'name'])
    @include('layout.select',['name'=>'abc_id','label'=>'ABC类','selected'=>null,'items'=>$abcs,'id'=>'id','field'=>'name'])
    @include('layout.select',['name'=>'plan_id','label'=>'检定计划','selected'=>null,'items'=>$plans,'id'=>'id','field'=>'name'])
    @include('layout.select',['name'=>'vulnerable','label'=>'易损','selected'=>null])
    @include('layout.input',['name'=>'requirement','label'=>'检定要求','type'=>'text','value'=>'','state'=>''])
@endsection
