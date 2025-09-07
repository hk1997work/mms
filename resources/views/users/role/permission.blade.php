@extends('layout.edit')
@section('content_title')
    @include('template.nav-tab',['tmp_name'=>'permission','tmp_label'=>'权限配置','tmp_active'=>true])
    @include('template.nav-tab',['tmp_name'=>'position','tmp_label'=>'岗位配置'])
@endsection
@section('content_form')
    <div class="tab-content">
        @include('template.checklist',['tmp_items'=>$permissions,'tmp_name'=>'permission','tmp_field'=>'description','tmp_checked'=>$myPermissions,'tmp_show'=>true])
        @include('template.checklist',['tmp_items'=>$positions,'tmp_name'=>'position','tmp_field'=>'name','tmp_checked'=>$myPositions])
    </div>
    <script>
        $('.tab-pane .btn-check').change(function () {
            let id = $(this).attr('id');
            let pid = $(this).data('pid');
            let ppid = $(this).data('ppid');
            let level = $(this).data('level');
            $('[data-pid="' + id + '"]').prop('checked', this.checked);
            $('[data-ppid="' + id + '"]').prop('checked', this.checked);
            if (this.checked) {
                $('#' + pid).prop('checked', this.checked);
                $('#' + ppid).prop('checked', this.checked);
            }
            if (level === 2 && $('[data-pid="' + pid + '"]:checked').length === 0) {
                $('#' + pid).prop('checked', false);
            }
        })
    </script>
@endsection
