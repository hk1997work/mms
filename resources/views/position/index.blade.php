@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav-btn',['tmp_label'=>'增加','tmp_menu'=>'position','tmp_class'=>'btn-add'])
        @include('template.nav-btn',['tmp_label'=>'删除','tmp_menu'=>'position','tmp_class'=>'btn-delete check-multiple'])
        @include('template.nav-btn',['tmp_label'=>'修改','tmp_menu'=>'position','tmp_class'=>'btn-edit check-single'])
        @include('template.nav-btn',['tmp_label'=>'查看','tmp_menu'=>'position','tmp_class'=>'btn-show check-single','tmp_pos'=>'left'])
        @include('template.nav-btn',['tmp_label'=>'上移','tmp_menu'=>'position','tmp_class'=>'btn-move check-single','tmp_type'=>1])
        @include('template.nav-btn',['tmp_label'=>'下移','tmp_menu'=>'position','tmp_class'=>'btn-move check-single','tmp_type'=>0])
    </ul>
    @include('template.table',['tmp_menu'=>'position','tmp_fields'=>['','岗位','','','','编号','数量'],'tmp_class'=>'table-tree'])
@endsection
@push('page-js-after-1')
    <script>
        $('.off-sidebar').on('change', '[name="position_id"]', function () {
            let form = $(this).closest('form')
            $.ajax({
                url: "/certificate_sn/" + form.find('[name="position_id"]').val(),
                success: function (data) {
                    data ? form.find('[name="sn"]').val(data).trigger('change') : notifications('序号加载失败');
                },
                error: function (xhr) {
                    xhr.status == 401 ? document.location.reload() : notifications('序号加载失败');
                }
            })
        })
    </script>
@endpush
