@extends('layout.edit')
@section('content_title')
    @include('template.nav-tab',['name'=>'permission','label'=>'权限配置','active'=>true])
    @include('template.nav-tab',['name'=>'position','label'=>'岗位配置'])
@endsection
@section('content_form')
    <div class="tab-content">
        @include('template.checklist',['items'=>$permissions,'name'=>'permission','field'=>'description','checked'=>$myPermissions,'show'=>true])
        @include('template.checklist',['items'=>$positions,'name'=>'position','field'=>'name','checked'=>$myPositions])
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
