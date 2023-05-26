@extends('layout.index')

@section('content_btn')
    <li class="nav-item">
        <a onclick="m_add('tool',{{$type->id}})" class="nav-link">增加量具</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown">{{$type->name}}<i class="ion-android-arrow-dropdown"></i></a>
        <div class="dropdown-menu">
            @foreach($types as $value)
                <a class="dropdown-item" href="?type_id={{$value->id}}">{{$value->name}}</a>
            @endforeach
        </div>
    </li>
@endsection

@section('content_table')
    <div class="row flex-row mt-3">
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
            <div class="widget widget-16 has-shadow">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                            <b class="counter">{{$tools->count()}}</b>
                            <b class="text-dangers">{{$tools->sum('total')}}</b>
                            <b class="total-visitors text-center">录入量具</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
            <div class="widget widget-16 has-shadow">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                            <b class="counter">{{$tools->where('active','!=',0)->count()}}</b>
                            <b class="text-dangers">{{$tools->sum('active')}}</b>
                            <b class="total-visitors text-center">在用量具</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
            <div class="widget widget-16 has-shadow">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                            <b class="counter">{{$tools->where('type_id',$type->id)->count()}}</b>
                            <b class="text-dangers">{{$tools->where('type_id',$type->id)->sum('total')}}</b>
                            <b class="total-visitors text-center">{{$type->name}}</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
            <div class="widget widget-16 has-shadow">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                            <b class="counter">{{$tools->where('type_id',$type->id)->where('active','!=',0)->count()}}</b>
                            <b class="text-dangers">{{$tools->where('type_id',$type->id)->sum('active')}}</b>
                            <b class="total-visitors text-center">{{$type->name}}在用</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
            <div class="widget widget-16 has-shadow">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                            <b class="counter text-dangers">{{$tools->where('type_id',$type->id)->sum('overtime')}}</b>
                            <b class="text-primary">{{$tools->sum('overtime')}}</b>
                            <b class="total-visitors text-center">长期闲置</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
            <div class="widget widget-16 has-shadow">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                            <b class="counter text-dangers">{{$tools->where('type_id',$type->id)->sum('exist')+$tools->where('type_id',$type->id)->sum('mistake')}}</b>
                            <b class="text-primary">{{$tools->sum('exist')+$tools->sum('mistake')}}</b>
                            <b class="total-visitors text-center">数据缺失</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table id="sorting-table" class="table table-hover mb-0">
        <thead>
        <tr>
            <th>器具名称</th>
            <th>规格型号</th>
            <th class="d-none d-xl-table-cell">测量范围</th>
            <th class="d-none d-xl-table-cell">精确度</th>
            <th class="d-none d-md-table-cell">检定周期</th>
            <th class="d-none d-lg-table-cell">ABC</th>
            <th class="d-none d-xl-table-cell">在用</th>
            <th class="d-none d-xl-table-cell">备用</th>
            <th class="d-none d-xl-table-cell">待检</th>
            <th class="d-none d-xl-table-cell">封存</th>
            <th class="d-none d-xl-table-cell">损坏</th>
            <th class="d-none d-xl-table-cell">报废</th>
            <th class="d-none d-md-table-cell">合计</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tools->where('type_id',$type->id) as $tool)
            <tr @if($tool->exist) class="bg-error" @endif>
                <td>{{$tool->instrument}}</td>
                <td>{{$tool->model}}</td>
                <td class="d-none d-xl-table-cell">{{$tool->limit}}</td>
                <td class="d-none d-xl-table-cell">{{$tool->accuracy}}</td>
                <td class="d-none d-md-table-cell">{{$tool->cycle}}</td>
                <td class="d-none d-lg-table-cell">{{$tool->abc}}</td>
                <td class="d-none d-xl-table-cell">@if($tool->active!=0)<span class="btn btn-outline-success btn-sm ripple" onclick="m_show('number','{{$tool->id}}_在用')">{{$tool->active}}</span>@endif</td>
                <td class="d-none d-xl-table-cell">@if($tool->standby!=0)<span class="btn btn-outline-info btn-sm ripple" onclick="m_show('number','{{$tool->id}}_备用')">{{$tool->standby}}</span>@endif</td>
                <td class="d-none d-xl-table-cell @if($tool->overtime) bg-error @endif">@if($tool->inactive!=0)<span class="btn btn-outline-warning btn-sm ripple" onclick=m_show('number','{{$tool->id}}_待检')>{{$tool->inactive}}</span>@endif</td>
                <td class="d-none d-xl-table-cell">@if($tool->deactive!=0)<span class="btn btn-outline-primary btn-sm ripple" onclick="m_show('number','{{$tool->id}}_封存')">{{$tool->deactive}}</span>@endif</td>
                <td class="d-none d-xl-table-cell">@if($tool->broken!=0)<span class="btn btn-outline-danger btn-sm ripple" onclick="m_show('number','{{$tool->id}}_损坏')">{{$tool->broken}}</span>@endif</td>
                <td class="d-none d-xl-table-cell">@if($tool->scrap!=0)<span class="btn btn-outline-dark btn-sm ripple" onclick="m_show('number','{{$tool->id}}_报废')">{{$tool->scrap}}</span>@endif</td>
                <td class="d-none d-md-table-cell  @if($tool->mistake) bg-error @endif">@if($tool->total!=0)<span class="btn btn-outline-secondary btn-sm ripple" onclick="m_show('tool',{{$tool->id}})">{{$tool->total}}</span>@endif</td>
                <td class="td-actions">
                    <a onclick="m_edit('tool',{{$tool->id}})"><i class="la la-edit edit"></i></a>
                    <a onclick="m_show('tool',{{$tool->id}})"><i class="la la-eye edit"></i></a>
                    <a onclick="m_delete('tool',{{$tool->id}})"><i class="la la-close delete"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
