@extends('layout.create')
@section('content_form')
    <input type="hidden" name="pid" value="{{$id}}">
    @include('layout.input',['name'=>'name','label'=>'标准','type'=>'text','value'=>'','state'=>''])
@endsection
