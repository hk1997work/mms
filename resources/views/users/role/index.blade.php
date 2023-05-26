@extends('layout.index')

@section('content_btn')
    <li class="nav-item"><a onclick="m_add('role')" class="nav-link">增加角色</a></li>
@endsection

@section('content_table')
    <div class="row flex-row mt-3">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
            <div class="widget widget-16 has-shadow">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                            <b class="counter">{{$roles->count()}}</b>
                            <b class="total-visitors text-center">角色</b>
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
                            <b class="counter text-dangers">{{$roles->filter(function($role) { return $role->user == '' || $role->permission == '';})->count() }}</b>
                            <b class="total-visitors text-center">无效角色</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table id="unsorted-table" class="table table-hover mb-0">
        <thead>
        <tr>
            <th>角色</th>
            <th>用户组</th>
            <th class="d-none d-sm-table-cell">权限组</th>
            <th class="d-none d-xl-table-cell">操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($roles as $role)
            <tr @if($role->permission==''||$role->user=='') class="bg-error" @endif>
                <td>{{$role->name}}</td>
                <td>{{$role->user}}</td>
                <td class="d-none d-sm-table-cell">{{$role->permission}}</td>
                <td class="td-actions d-none d-xl-table-cell">
                    <a onclick="m_edit('role',{{$role->id}})"><i class="la la-edit edit"></i></a>
                    <a onclick="m_edit('permissions',{{$role->id}})"><i class="la la-cogs edit"></i></a>
                    <a onclick="m_delete('role',{{$role->id}})"><i class="la la-close delete"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
