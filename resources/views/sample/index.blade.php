@extends('layout.index')
@section('content_btn')
    @include('template.nav-btn',['tmp_label'=>'查看','tmp_menu'=>'sample','tmp_class'=>'btn-show','tmp_pos'=>'up'])
    @include('template.nav-btn',['tmp_label'=>'下载','tmp_menu'=>'sample','tmp_class'=>'submit-download'])
    <form id="form-download" action="/sample" method="post">
        {{csrf_field()}}
        <input type="hidden" id="date-download" name="date">
    </form>
@endsection
@section('content_table')
    @include('template.table',['tmp_menu'=>"sample?id=$dates[0]",'tmp_fields'=>['序号','使用岗位','使用者','器具名称','规格型号','出厂编号','检测范围','检定合格证','备注'],'tmp_class'=>'table-all table-unselect'])
    <div id="timeline"></div>
@endsection
@push('page-js-after-1')
    <script>
        $(document).ready(function () {
            let container = document.getElementById('timeline');
            let arr = [];
            @foreach($dates as $date)
            arr.push(
                {
                    id: '{{ $date }}',
                    content: '{{ $date }}',
                    start: "{{ $date }} 00:00:00",
                    end: "{{ $date }} 23:59:59",
                }
            )
            @endforeach
            let items = new vis.DataSet(arr);
            let options = {
                height: '100px',
                stack: false,
                min: "{{$dates[count($dates)-1]}} 00:00:00",
                max: "{{$dates[0]}} 23:59:59",
            };
            let timeline = new vis.Timeline(container, items, options);
            let selected = "{{$dates[0]}}";
            $('.btn-download').data('id', selected);
            $('.btn-show').data('id', selected);
            timeline.setSelection([selected]);
            timeline.setWindow('{{date('Y-m-d',strtotime($dates[0].'-1 month'))}}', '{{$dates[0]}} 23:59:59', {animation: true});
            timeline.on('select', function (properties) {
                if (properties.items.length > 0) {
                    selected = properties.items[0];
                    $('#index-table').attr('data-menu', 'sample?id=' + selected);
                    $('.btn-download').data('id', selected);
                    $('.btn-show').data('id', selected);
                    dataTable.ajax.url('/ajax_sample?id=' + selected).load();
                }
                if (properties.items.length == 0) {
                    timeline.setSelection([selected]);
                }
            });
            $('.submit-download').click(function () {
                $('#date-download').val(selected)
                $('#form-download').submit()
            })
        });
    </script>
@endpush
