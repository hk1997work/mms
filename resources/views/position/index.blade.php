@extends('layout.index')

@section('content_btn')
    <li class="nav-item d-none d-sm-table-cell"><a onclick="m_add('position',{{$type_id}})" class="nav-link">增加单位</a></li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown">{{$type_id?$positions->find($type_id)->name1:'全部'}}<i class="ion-android-arrow-dropdown"></i></a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="?type_id=0">全部</a>
            @foreach($positions->where('level',2) as $position)
                <a class="dropdown-item" href="?type_id={{$position->id}}">{{$position->name1}}</a>
            @endforeach
        </div>
    </li>
@endsection

@section('content_table')
    <div class="row flex-row mt-3">
        @if($type_id)
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
                <div class="widget widget-16 has-shadow">
                    <div class="widget-body">
                        <div class="row">
                            <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                <b class="counter text-warning">{{$positions->where('level',3)->count()}}</b>
                                <b class="total-visitors text-center">单位</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
                <div class="widget widget-16 has-shadow">
                    <div class="widget-body">
                        <div class="row">
                            <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                <b class="counter text-success">{{$positions->where('level',4)->count()}}</b>
                                <b class="total-visitors text-center">部门</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
                <div class="widget widget-16 has-shadow">
                    <div class="widget-body">
                        <div class="row">
                            <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                <b class="counter text-info">{{$positions->where('level',5)->count()}}</b>
                                <b class="total-visitors text-center">岗位</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
                <div class="widget widget-16 has-shadow">
                    <div class="widget-body">
                        <div class="row">
                            <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                <b class="counter text-dangers">{{$positions->where('total',0)->where('sign',0)->count()}}</b>
                                <b class="total-visitors text-center">无效岗位</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="widget widget-16 has-shadow">
                    <div class="widget-body">
                        <div class="row">
                            <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                <b class="counter text-success">{{$positions->where('level',2)->count()}}</b>
                                <b class="total-visitors text-center">分类</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="widget widget-16 has-shadow">
                    <div class="widget-body">
                        <div class="row">
                            <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                                <b class="counter text-dangers">{{$positions->where('total',0)->where('sign',0)->count()}}</b>
                                <b class="total-visitors text-center">无效岗位</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <table id="unsorted-table" class="table table-hover mb-0">
        <thead>
        <tr>
            <th>单位</th>
            @if($type_id==0)
                <th>分类</th>
            @else
                <th>部门</th>
                <th>岗位</th>
                <th class="d-none d-sm-table-cell">编号</th>
            @endif
            <th>数量</th>
            <th class="d-none d-xl-table-cell">操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($positions->whereIn('level',$type_id==0?[1,2]:[3,4,5]) as $position)
            @if($type_id==0||in_array($type_id,explode(',',$position->str)))
                <tr @if($position->total==0&&$position->sign==0) class="bg-error" @endif>
                    @switch($position->level)
                        @case(1)
                        <td><span class="btn btn-warning btn-sm ripple" onclick="m_show('position',{{$position->id}})">{{$position->name1}}</span></td>
                        <td><span class="btn btn-outline-secondary btn-sm ripple" onclick="m_add('position',{{$position->id}})">增加分类</span></td>
                        <td>@if(isset($position->count))<span class="btn btn-outline-secondary btn-sm ripple">{{$position->count}}@if($position->total-$position->count>0)/{{$position->total-$position->count}}@endif</span>@endif</td>
                        @break
                        @case(2)
                        <td>{{$position->name2}}</td>
                        <td><span class="btn btn-success btn-sm ripple" onclick="m_show('position',{{$position->id}})">{{$position->name1}}</span></td>
                        <td>@if(isset($position->count))<a class="btn btn-outline-secondary btn-sm ripple" href="?type_id={{$position->id}}">{{$position->count}}@if($position->total-$position->count>0)/{{$position->total-$position->count}}@endif</a>@endif</td>
                        @break
                        @case(3)
                        <td><span class="btn btn-warning btn-sm ripple" onclick="m_show('position',{{$position->id}})">{{$position->name1}}</span></td>
                        <td><span class="btn btn-outline-secondary btn-sm ripple" onclick="m_add('position',{{$position->id}})">增加部门</span></td>
                        <td></td>
                        <td class="d-none d-sm-table-cell"></td>
                        <td>@if(isset($position->count))<span class="btn @if($position->sign==0) btn-outline-secondary @else btn-outline-danger @endif btn-sm ripple" onclick="sign({{$position->id}})">{{$position->count}}@if($position->total-$position->count>0)/{{$position->total-$position->count}}@endif</span>@endif </td>
                        @break
                        @case(4)
                        <td>{{$position->name2}}</td>
                        <td><span class="btn btn-success btn-sm ripple" onclick="m_show('position',{{$position->id}})">{{$position->name1}}</span></td>
                        <td><span class="btn btn-outline-secondary btn-sm ripple" onclick="m_add('position',{{$position->id}})">增加岗位</span></td>
                        <td class="d-none d-sm-table-cell"></td>
                        <td>@if(isset($position->count))<span class="btn @if($position->sign==0) btn-outline-secondary @else btn-outline-danger @endif btn-sm ripple" onclick="sign({{$position->id}})">{{$position->count}}@if($position->total-$position->count>0)/{{$position->total-$position->count}}@endif</span>@endif</td>
                        @break
                        @case(5)
                        <td>{{$position->name3}}</td>
                        <td>{{$position->name2}}</td>
                        <td><span class="btn btn-info btn-sm ripple" onclick="m_show('position',{{$position->id}})">{{$position->name1}}</span></td>
                        <td class="d-none d-sm-table-cell">@if(isset($position->code))<span class="btn btn-outline-info btn-sm ripple">{{$position->code}}</span>@endif</td>
                        <td>@if(isset($position->count))<span class="btn @if($position->sign==0) btn-outline-secondary @else btn-outline-danger @endif btn-sm ripple" onclick="sign({{$position->id}})">{{$position->count}}@if($position->total-$position->count>0)/{{$position->total-$position->count}}@endif</span>@endif</td>
                        @break
                    @endswitch
                    <td class="td-actions d-none d-xl-table-cell">
                        <a onclick="up(this,{{$position->id}})"><i class="la la-angle-up edit"></i></a>
                        <a onclick="down(this,{{$position->id}})"><i class="la la-angle-down edit"></i></a>
                        <a onclick="m_edit('position',{{$position->id}})"><i class="la la-edit edit"></i></a>
                        <a onclick="m_delete('position',{{$position->id}},'{{$position->name1}}')"><i class="la la-close delete"></i></a>
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
@endsection
