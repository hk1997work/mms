@extends('layout.index')

@section('content_btn')
    <li class="nav-item"><a onclick="m_add('standard')" class="nav-link">增加标准</a></li>
@endsection

@section('content_table')
    <div class="row flex-row mt-3">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
            <div class="widget widget-16 has-shadow">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                            <b class="counter text-primary">{{$standards->where('level',2)->count()}}</b>
                            <b class="text-primary">{{$standards->where('level',1)->count()}}</b>
                            <b class="total-visitors text-center">录入标准</b>
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
                            <b class="counter text-dangers">{{$standards->where('level',2)->where('count',0)->count()}}</b>
                            <b class="text-dangers">{{$standards->where('level',1)->where('count',0)->count()}}</b>
                            <b class="total-visitors text-center">无效标准</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table id="unsorted-table" class="table table-hover mb-0">
        <thead>
        <tr>
            <th>标准</th>
            <th class="d-none d-sm-table-cell">版本名称</th>
            <th>使用次数</th>
            <th class="d-none d-xl-table-cell">操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($standards as $standard)
            <tr @if(!$standard->count) class="bg-error" @endif>
                @if($standard->level==1)
                    <td><span class="btn btn-warning btn-sm ripple" onclick="m_edit('standard',{{$standard->id}})">{{$standard->name1}}</span></td>
                    <td class="d-none d-sm-table-cell"><span class="btn btn-outline-secondary btn-sm ripple" onclick="m_add('standard',{{$standard->id}})" data-toggle="modal" data-target="#modal">增加标准</span></td>
                    <td><span class="btn btn-outline-secondary btn-sm ripple">{{$standard->count}}</span></td>
                @else
                    <td class="d-none d-sm-table-cell">{{$standard->name1}}</td>
                    <td><span class="btn btn-success btn-sm ripple" onclick="m_edit('standard',{{$standard->id}})" title="{{$standard->name2}}">{{Str::limit($standard->name2,40,'...')}}</span></td>
                    <td><span class="btn btn-outline-secondary btn-sm ripple" onclick="m_show('standard','{{$standard->id}}')">{{$standard->count}}</span></td>
                @endif
                    <td class="td-actions d-none d-xl-table-cell">
                        <a onclick="m_edit('standard',{{$standard->id}})"><i class="la la-edit edit"></i></a>
                        <a onclick="m_delete('standard',{{$standard->id}})"><i class="la la-close delete"></i></a>
                    </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

