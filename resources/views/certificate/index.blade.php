@extends('layout.index')
@section('content_btn')
    @if($menu=='active'||$menu=='invalid')
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{$type->name}}<i class="ion-android-arrow-dropdown"></i></a>
            <ul class="dropdown-menu dropdown-menu-end">
                @foreach($types as $value)
                    <li><a class="dropdown-item" href="?id={{$value->id}}">{{$value->name}}</a></li>
                @endforeach
            </ul>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{$position?$position->parent->name:'全部'}}<i class="ion-android-arrow-dropdown"></i></a>
            <ul class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="?id={{$type->id}}">全部</a>
                @foreach($positions as $value)
                    <li><a class="dropdown-item" href="?id={{$type->id}}&position={{$value->id}}">{{$value->parent->name}}</a></li>
                @endforeach
            </ul>
        </li>
    @endif
@endsection
@section('content_table')
    <ul class="nav">
        @if($menu=='active')
            <li class="nav-item"><a class="nav-link btn-add" data-menu="certificate" href="#" data-pos="left" data-id="{{$type->id}}">增加</a></li>
        @endif
        <li class="nav-item"><a class="nav-link btn-delete check-multiple" data-menu="certificate" href="#" data-pos="right">删除</a></li>
        <li class="nav-item"><a class="nav-link btn-open check-multiple" data-menu="certificate" href="#">打开</a></li>
        <li class="nav-item"><a class="nav-link btn-download check-multiple" data-menu="certificate" href="#">下载</a></li>
        @if($menu=='active')
            <li class="nav-item"><a class="nav-link btn-edit check-multiple" data-menu="print" href="#" data-pos="right">打印标签</a></li>
            <li class="nav-item"><a class="nav-link btn-show check-multiple" data-menu="supervision" href="#" data-pos="right">监督检查</a></li>
        @endif
        <li class="nav-item"><a class="nav-link btn-edit check-single" data-menu="certificate" href="#" data-pos="left">修改</a></li>
        <li class="nav-item"><a class="nav-link btn-show check-single" data-menu="certificate" href="#" data-pos="up">查看</a></li>
    </ul>
    <table id="index-table" data-menu="certificate?id={{$type->id}}&path={{$menu}}{{$position?'&position='.$position->id:''}}" class="table table-hover">
        <thead>
        <tr>
            <th></th>
            <th>序号</th>
            <th>岗位</th>
            <th>证书编号</th>
            <th>器具名称</th>
            <th>规格型号</th>
            <th>出厂编号</th>
            <th>检定日期</th>
            <th>有效期</th>
            <th>检定部门</th>
            <th>备注</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection
@push('page-js-after-1')
    <script src="/admin/assets/js/pages/certificate.js"></script>
@endpush
