@extends('layout.index')

@section('content_btn')
    <li class="nav-item d-none d-sm-table-cell"><a onclick="m_add('position',{{$type_id}})" class="nav-link">增加单位</a></li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown">{{$type_id?$positions->find($type_id)->name:'全部'}}<i class="ion-android-arrow-dropdown"></i></a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="?type_id=0">全部</a>
            @foreach($positions->where('level',2) as $position)
                <a class="dropdown-item" href="?type_id={{$position->id}}">{{$position->name}}</a>
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
                                <b class="counter text-dangers">{{$positions->where('count',0)->where('sign',0)->count()}}</b>
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
                                <b class="counter text-dangers">{{$positions->where('count',0)->where('sign',0)->count()}}</b>
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
        @if($type_id==0)
            @foreach($positions->where('level',1) as $position_1)
                <tr @if($position_1->count==0&&$position_1->sign==0) class="bg-error" @endif>
                    <td><span class="btn btn-warning btn-sm ripple" onclick="m_show('position',{{$position_1->id}})">{{$position_1->name}}</span></td>
                    <td><span class="btn btn-outline-secondary btn-sm ripple" onclick="m_add('position',{{$position_1->id}})">增加分类</span></td>
                    <td>@if(isset($position_1->count))<span class="btn btn-outline-secondary btn-sm ripple">{{$position_1->count}}</span>@endif</td>
                    <td class="td-actions d-none d-xl-table-cell">
                        <a onclick="up(this,{{$position_1->id}})"><i class="la la-angle-up edit"></i></a>
                        <a onclick="down(this,{{$position_1->id}})"><i class="la la-angle-down edit"></i></a>
                        <a onclick="m_edit('position',{{$position_1->id}})"><i class="la la-edit edit"></i></a>
                        <a onclick="m_delete('position',{{$position_1->id}})"><i class="la la-close delete"></i></a>
                    </td>
                </tr>
                @foreach($positions->where('pid',$position_1->id) as $position_2)
                    <tr @if($position_2->count==0&&$position_2->sign==0) class="bg-error" @endif>
                        <td>{{$position_1->name}}</td>
                        <td><span class="btn btn-success btn-sm ripple" onclick="m_show('position',{{$position_2->id}})">{{$position_2->name}}</span></td>
                        <td>@if(isset($position_2->count))<a class="btn btn-outline-secondary btn-sm ripple" href="?type_id={{$position_2->id}}">{{$position_2->count}}</a>@endif</td>
                        <td class="td-actions d-none d-xl-table-cell">
                            <a onclick="up(this,{{$position_2->id}})"><i class="la la-angle-up edit"></i></a>
                            <a onclick="down(this,{{$position_2->id}})"><i class="la la-angle-down edit"></i></a>
                            <a onclick="m_edit('position',{{$position_2->id}})"><i class="la la-edit edit"></i></a>
                            <a onclick="m_delete('position',{{$position_2->id}})"><i class="la la-close delete"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        @else
            @foreach($positions->where('pid',$type_id) as $position_3)
                <tr @if($position_3->count==0&&$position_3->sign==0) class="bg-error" @endif>
                    <td><span class="btn btn-warning btn-sm ripple" onclick="m_show('position',{{$position_3->id}})">{{$position_3->name}}</span></td>
                    <td><span class="btn btn-outline-secondary btn-sm ripple" onclick="m_add('position',{{$position_3->id}})">增加部门</span></td>
                    <td></td>
                    <td class="d-none d-sm-table-cell">@if(isset($position_3->code))<span class="btn btn-info btn-sm ripple">{{$position_3->code}}</span>@endif</td>
                    <td>@if(isset($position_3->count))<span class="btn @if($position_3->sign==0) btn-outline-secondary @else btn-outline-danger @endif btn-sm ripple" onclick="sign({{$position_3->id}})">{{$position_3->count}}</span>@endif</td>
                    <td class="td-actions d-none d-xl-table-cell">
                        <a onclick="up(this,{{$position_3->id}})"><i class="la la-angle-up edit"></i></a>
                        <a onclick="down(this,{{$position_3->id}})"><i class="la la-angle-down edit"></i></a>
                        <a onclick="m_edit('position',{{$position_3->id}})"><i class="la la-edit edit"></i></a>
                        <a onclick="m_delete('position',{{$position_3->id}})"><i class="la la-close delete"></i></a>
                    </td>
                </tr>
                @foreach($positions->where('pid',$position_3->id) as $position_4)
                    <tr @if($position_4->count==0&&$position_4->sign==0) class="bg-error" @endif>
                        <td>{{$position_3->name}}</td>
                        <td><span class="btn btn-success btn-sm ripple" onclick="m_show('position',{{$position_4->id}})">{{$position_4->name}}</span></td>
                        <td><span class="btn btn-outline-secondary btn-sm ripple" onclick="m_add('position',{{$position_4->id}})">增加岗位</span></td>
                        <td class="d-none d-sm-table-cell">@if(isset($position_4->code))<span class="btn btn-outline-info btn-sm ripple">{{$position_4->code}}</span>@endif</td>
                        <td>@if(isset($position_4->count))<span class="btn @if($position_4->sign==0) btn-outline-secondary @else btn-outline-danger @endif btn-sm ripple" onclick="sign({{$position_4->id}})">{{$position_4->count}}</span>@endif</td>
                        <td class="td-actions d-none d-xl-table-cell">
                            <a onclick="up(this,{{$position_4->id}})"><i class="la la-angle-up edit"></i></a>
                            <a onclick="down(this,{{$position_4->id}})"><i class="la la-angle-down edit"></i></a>
                            <a onclick="m_edit('position',{{$position_4->id}})"><i class="la la-edit edit"></i></a>
                            <a onclick="m_delete('position',{{$position_4->id}})"><i class="la la-close delete"></i></a>
                        </td>
                    </tr>
                    @foreach($positions->where('pid',$position_4->id) as $position_5)
                        <tr @if($position_5->count==0&&$position_5->sign==0) class="bg-error" @endif>
                            <td>{{$position_3->name}}</td>
                            <td>{{$position_4->name}}</td>
                            <td><span class="btn btn-info btn-sm ripple" onclick="m_show('position',{{$position_5->id}})">{{$position_5->name}}</span></td>
                            <td class="d-none d-sm-table-cell">@if(isset($position_5->code))<span class="btn btn-outline-info btn-sm ripple">{{$position_5->code}}</span>@endif</td>
                            <td>@if(isset($position_5->count))<span class="btn @if($position_5->sign==0) btn-outline-secondary @else btn-outline-danger @endif btn-sm ripple" onclick="sign({{$position_5->id}})">{{$position_5->count}}</span>@endif</td>
                            <td class="td-actions d-none d-xl-table-cell">
                                <a onclick="up(this,{{$position_5->id}})"><i class="la la-angle-up edit"></i></a>
                                <a onclick="down(this,{{$position_5->id}})"><i class="la la-angle-down edit"></i></a>
                                <a onclick="m_edit('position',{{$position_5->id}})"><i class="la la-edit edit"></i></a>
                                <a onclick="m_delete('position',{{$position_5->id}})"><i class="la la-close delete"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endif
        </tbody>
    </table>
@endsection
