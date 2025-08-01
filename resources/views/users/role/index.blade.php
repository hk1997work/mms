@extends('layout.index')
@section('content_table')
    <div class="btn-group mb-2">
        <ul class="button-nav nav nav-tabs" role="tablist">
            <li><a class="btn-add" data-pos="right" data-menu="role" href="#">增加</a></li>
            <li><a class="btn-delete check-multiple" data-pos="right" data-menu="role" href="#">删除</a></li>
            <li><a class="btn-edit check-multiple" data-pos="right" data-menu="permissions" href="#">配置</a></li>
            <li><a class="btn-edit check-single" data-pos="right" data-menu="role" href="#">修改</a></li>
        </ul>
    </div>
    <table id="index-table" data-menu="role" class="table table-hover mb-0">
        <thead>
        <tr>
            <th style="width:5%;">
                <div class="styled-checkbox">
                    <input type="checkbox" name="check-all" class="check-all" id="check-all">
                    <label for="check-all"></label>
                </div>
            </th>
            <th>角色</th>
            <th>用户组</th>
            <th>权限组</th>
            <th>岗位组</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection
@push('page-js-after-1')
    <script>
        $(document).on('mouseover', '[data-toggle="popover"]', function() {
            $('[data-toggle="popover"]').popover();
            $(this).popover('show');
        });
    </script>
@endpush