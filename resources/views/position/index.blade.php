@extends('layout.index')
@section('content_table')
    <ul class="nav">
        <li class="nav-item"><a class="nav-link btn-add" data-menu="position" href="#" data-pos="right">增加</a></li>
        <li class="nav-item"><a class="nav-link btn-delete check-multiple" data-menu="position" href="#" data-pos="right">删除</a></li>
        <li class="nav-item"><a class="nav-link btn-edit check-single" data-menu="position" href="#" data-pos="right">修改</a></li>
        <li class="nav-item"><a class="nav-link btn-show check-single" data-menu="position" href="#" data-pos="left">查看</a></li>
        <li class="nav-item"><a class="nav-link btn-move check-single" data-menu="position" href="#" data-type="1">上移</a></li>
        <li class="nav-item"><a class="nav-link btn-move check-single" data-menu="position" href="#" data-type="0">下移</a></li>
    </ul>
    <table id="index-table" data-menu="position" class="table table-hover table-tree">
        <thead>
        <tr>
            <th></th>
            <th>岗位</th>
            <th></th>
            <th></th>
            <th></th>
            <th>编号</th>
            <th>数量</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection
@push('page-js-after-1')
    <script>
        $('.off-sidebar').on('change', '[name="position_id"]', function () {
            let form = $(this).closest('form')
            $.ajax({
                url: "/certificate_sn/" + form.find('[name="position_id"]').val(),
                success: function (data) {
                    data
                        ? form.find('[name="sn"]').val(data).trigger('change')
                        : notifications('序号加载失败');
                },
                error: function (xhr) {
                    xhr.status == 401 ? document.location.reload() : notifications('序号加载失败')
                }
            })
        })
    </script>
@endpush
