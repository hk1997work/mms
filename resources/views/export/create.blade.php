@extends('layout.create')
@section('content_form')
    @include('template.table',['tmp_menu'=>'','tmp_fields'=>['','序号','岗位','器具名称','规格型号'],'tmp_id'=>'off-sidebar','tmp_class'=>'table-data','tmp_items'=>$certificates])
    <script>
        $(document).ready(function () {
            let selectedOrders = "{{ $settings }}";
            selectedOrders = selectedOrders ? selectedOrders.split(',') : [];
            offSidebarDataTable.on('draw', function () {
                offSidebarDataTable.rows(function (idx, data, node) {
                    return selectedOrders.includes(data[1]);
                }).select();
            });
        });
    </script>
@endsection