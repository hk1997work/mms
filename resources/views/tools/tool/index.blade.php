@extends('layout.index')
@section('content_btn')
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{$type->name}}<i class="ion-android-arrow-dropdown"></i></a>
        <ul class="dropdown-menu dropdown-menu-end">
            @foreach($types as $value)
                <li><a class="dropdown-item" href="?id={{$value->id}}">{{$value->name}}</a></li>
            @endforeach
        </ul>
    </li>
@endsection
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['label'=>'增加','menu'=>'tool','class'=>'btn-add','id'=>$type->id])
        @include('template.nav-btn',['label'=>'删除','menu'=>'tool','class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['label'=>'修改','menu'=>'tool','class'=>'btn-edit check-single'])
        @include('template.nav-btn',['label'=>'查看','menu'=>'tool','class'=>'btn-show check-single','pos'=>'left'])
    </ul>
    @include('template.table',['menu'=>'tool?id='.$type->id,'fields'=>['','器具名称','规格型号','测量范围','精确度','检定周期','ABC','在用','待检','封存','损坏','报废','合计','易损','检定要求']])
@endsection
