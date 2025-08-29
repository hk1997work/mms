@extends('layout.index')
@section('content_table')
    <ul class="nav">
        @include('template.nav',['label'=>'增加','menu'=>'position','class'=>'btn-add'])
        @include('template.nav',['label'=>'删除','menu'=>'position','class'=>'btn-delete check-multiple'])
        @include('template.nav',['label'=>'修改','menu'=>'position','class'=>'btn-edit check-single'])
        @include('template.nav',['label'=>'查看','menu'=>'position','class'=>'btn-show check-single','pos'=>'left'])
        @include('template.nav',['label'=>'上移','menu'=>'position','class'=>'btn-move check-single','type'=>1])
        @include('template.nav',['label'=>'下移','menu'=>'position','class'=>'btn-move check-single','type'=>0])
    </ul>
    @include('template.table',['menu'=>'position','fields'=>['岗位','','','','编号','数量'],'class'=>'table-tree'])
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
