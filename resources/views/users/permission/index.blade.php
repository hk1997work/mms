@extends('layout.index')

@section('content_btn')
    <li class="nav-item"><a onclick="m_add('permission')" class="nav-link">增加权限</a></li>
@endsection

@section('content_table')
    <div class="row flex-row mt-3">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
            <div class="widget widget-16 has-shadow">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                            <b class="counter text-warning">{{$permissions->where('level',1)->count()}}</b>
                            <b class="total-visitors text-center">一级权限</b>
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
                            <b class="counter text-success">{{$permissions->where('level',2)->count()}}</b>
                            <b class="total-visitors text-center">二级权限</b>
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
                            <b class="counter text-info">{{$permissions->where('level',3)->count()}}</b>
                            <b class="total-visitors text-center">三级权限</b>
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
                            <b class="counter text-dangers">{{$permissions->where('role','')->count()}}</b>
                            <b class="total-visitors text-center">无效权限</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table id="unsorted-table" class="table table-hover mb-0">
        <thead>
        <tr>
            <th>一级权限</th>
            <th>二级权限</th>
            <th>三级权限</th>
            <th class="d-none d-sm-table-cell">权限名称</th>
            <th class="d-none d-xl-table-cell">角色组</th>
            <th class="d-none d-xl-table-cell">操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($permissions as $permission)
            <tr @if($permission->role=='') class="bg-error" @endif>
                @switch($permission->level)
                    @case(1)
                    <td><span class="btn btn-warning btn-sm ripple" onclick="m_edit('permission',{{$permission->id}})">{{$permission->description1}}</span></td>
                    <td><span class="btn btn-outline-secondary btn-sm ripple" onclick="m_add('permission',{{$permission->id}})">增加权限</span></td>
                    <td></td>
                    @break
                    @case(2)
                    <td>{{$permission->description2}}</td>
                    <td><span class="btn btn-success btn-sm ripple" onclick="m_edit('permission',{{$permission->id}})">{{$permission->description1}}</span></td>
                    <td><span class="btn btn-outline-secondary btn-sm ripple" onclick="m_add('permission',{{$permission->id}})">增加权限</span></td>
                    @break
                    @case(3)
                    <td>{{$permission->description3}}</td>
                    <td>{{$permission->description2}}</td>
                    <td><span class="btn btn-info btn-sm ripple" onclick="m_edit('permission',{{$permission->id}})">{{$permission->description1}}</span></td>
                    @break
                @endswitch
                <td class="d-none d-sm-table-cell">{{$permission->name}}</td>
                <td class="d-none d-xl-table-cell">{{$permission->role}}</td>
                <td class="td-actions d-none d-xl-table-cell">
                    <a onclick="up(this,{{$permission->id}})"><i class="la la-angle-up edit"></i></a>
                    <a onclick="down(this,{{$permission->id}})"><i class="la la-angle-down edit"></i></a>
                    <a onclick="m_edit('permission',{{$permission->id}})"><i class="la la-edit edit"></i></a>
                    <a onclick="m_delete('permission',{{$permission->id}})"><i class="la la-close delete"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
