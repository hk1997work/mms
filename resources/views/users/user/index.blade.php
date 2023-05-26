@extends('layout.index')

@section('content_btn')
    <li class="nav-item"><a onclick="m_add('user')" class="nav-link">增加用户</a></li>
@endsection

@section('content_table')
    <div class="row flex-row mt-3">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
            <div class="widget widget-16 has-shadow">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-xl-12 d-flex flex-column justify-content-center align-items-center">
                            <b class="counter">{{$users->count()}}</b>
                            <b class="total-visitors text-center">用户</b>
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
                            <b class="counter text-dangers">{{$users->where('role','')->count()}}</b>
                            <b class="total-visitors text-center">无效用户</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table id="sorting-table" class="table table-hover mb-0">
        <thead>
        <tr>
            <th>用户名</th>
            <th>角色组</th>
            <th class="d-none d-xl-table-cell">操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr @if($user->role=='') class="bg-error" @endif>
                <td>{{$user->username}}</td>
                <td>{{$user->role}}</td>
                <td class="td-actions d-none d-xl-table-cell">
                    <a onclick="m_edit('user',{{$user->id}})"><i class="la la-edit edit"></i></a>
                    <a onclick="m_edit('roles',{{$user->id}})"><i class="la la-cogs edit"></i></a>
                    <a onclick="m_delete('user',{{$user->id}})"><i class="la la-close delete"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
