@extends('layout.index')
@section('content_btn')
    @if($menu=='active'||$menu=='invalid')
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown">
                {{$type->level==2?$type->name:$types->find($type->pid)->name}}
                <i class="ion-android-arrow-dropdown"></i>
            </a>
            <div class="dropdown-menu">
                @foreach($types->where('level',2) as $value)
                    <a class="dropdown-item" href="?id={{$value->id}}">{{$value->name}}</a>
                @endforeach
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown">
                {{$type->level==2?'全部':$type->name}}
                <i class="ion-android-arrow-dropdown"></i>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="?id={{$type->level==2?$type->id:$type->pid}}">全部</a>
                @foreach($types->where('pid',$type->level==2?$type->id:$type->pid) as $value)
                    <a class="dropdown-item" href="?id={{$value->id}}">{{$value->name}}</a>
                @endforeach
            </div>
        </li>
    @endif
@endsection
@section('content_table')
    <div class="btn-group mb-3">
        <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-3" role="tablist">
            @if($menu=='active')
                <li><a class="btn-add" data-menu="certificate" data-id='{{$type->id}}' data-pos="left" href="#">增加</a></li>
            @endif
            <li><a class="btn-delete check-multiple" data-menu="certificate" data-pos="right" href="#">删除</a></li>
            <li><a class="btn-open check-multiple" data-menu="certificate" href="#">打开</a></li>
            <li><a class="btn-download check-multiple" data-menu="certificate" href="#">下载</a></li>
            @if($menu=='active')
                <li><a class="btn-edit check-multiple" data-menu="print" data-pos="right" href="#">打印标签</a></li>
                <li><a class="btn-show check-multiple" data-menu="supervision" data-pos="right" href="#">监督检查</a></li>
            @endif
            <li><a class="btn-edit check-single" data-menu="certificate" data-pos="left" href="#">修改</a></li>
            <li><a class="btn-show check-single" data-menu="certificate" data-pos="up" href="#">查看</a></li>
        </ul>
    </div>
    <table id="index-table" data-menu="certificate?id={{$type->id}}&path={{$menu}}" class="table table-hover mb-0">
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
