@extends('layout.index')

@section('content_table')
    <div class="row flex-row mt-3">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
            <div class="widget widget-16 has-shadow">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                            <b class="counter text-warning">{{$parameters->where('level',1)->count()}}</b>
                            <b class="total-visitors text-center">分类</b>
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
                            <b class="counter text-dangers">{{$parameters->where('level',1)->where('count',null)->count()}}</b>
                            <b class="total-visitors text-center">无效分类</b>
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
                            <b class="counter text-success">{{$parameters->where('level',2)->count()}}</b>
                            <b class="total-visitors text-center">参数</b>
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
                            <b class="counter text-dangers">{{$parameters->where('level',2)->where('count',null)->count()}}</b>
                            <b class="total-visitors text-center">无效参数</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table id="unsorted-table" class="table table-hover mb-0">
        <thead>
        <tr>
            <th>分类</th>
            <th>参数</th>
            <th>数量</th>
            <th class="d-none d-xl-table-cell">操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($parameters as $parameter)
            <tr @if($parameter->count==null) class="bg-error" @endif>
                @switch($parameter->level)
                    @case(1)
                    <td><span class="btn btn-warning btn-sm ripple" onclick="m_edit('parameter',{{$parameter->id}})">{{$parameter->name1}}</span></td>
                    <td><span class="btn btn-outline-secondary btn-sm ripple" onclick="m_add('parameter',{{$parameter->id}})">增加参数</span></td>
                    <td><span class="btn btn-outline-secondary btn-sm ripple">{{isset($parameter->count)?$parameter->count:0}}</span></td>
                    @break
                    @case(2)
                    <td>{{$parameter->name2}}</td>
                    <td><span class="btn btn-success btn-sm ripple" onclick="m_edit('parameter',{{$parameter->id}})">{{$parameter->name1}}</span></td>
                    <td><span class="btn btn-outline-secondary btn-sm ripple">{{isset($parameter->count)?$parameter->count:0}}</span></td>
                    @break
                @endswitch
                <td class="td-actions d-none d-xl-table-cell">
                    <a onclick="up(this,{{$parameter->id}})"><i class="la la-angle-up edit"></i></a>
                    <a onclick="down(this,{{$parameter->id}})"><i class="la la-angle-down edit"></i></a>
                    <a onclick="m_edit('parameter',{{$parameter->id}})"><i class="la la-edit edit"></i></a>
                    <a onclick="m_delete('parameter',{{$parameter->id}},'{{$parameter->name1}}')"><i class="la la-close delete"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
