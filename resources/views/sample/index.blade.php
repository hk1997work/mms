@extends('layout.index')
@section('content_btn')
    <li class="nav-item"><a class="nav-link btn-show check-single" data-pos="up" data-menu="sample" data-id="{{$dates[0]}}" href="#">查看</a></li>
    <li class="nav-item"><a class="nav-link btn-download" href="#">下载</a></li>
    <form id="form-download" action="/sample" method="post">
        {{csrf_field()}}
        <input type="hidden" id="date-download" name="date" value="{{$dates[0]}}">
        <input type="hidden" id="id-download" name="id" value="{{implode(',',$id[$dates[0]])}}">
    </form>
@endsection
@section('content_table')
    <div class="btn-group mb-3">
        <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-3" role="tablist">
        </ul>
    </div>
    <table id="index-table" data-menu="sample?id={{implode(',',$id[$dates[0]])}}"
           class="table table-hover mb-0 unsorted nocheck tl-100">
        <thead>
        <tr>
            <th>序号</th>
            <th>使用岗位</th>
            <th>使用者</th>
            <th>器具名称</th>
            <th>规格型号</th>
            <th>出厂编号</th>
            <th>检测范围</th>
            <th>检定合格证</th>
            <th>备注</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
    <div id="timeline"></div>
@endsection
@push('page-js-after-1')
    <script src="/admin/assets/vendors/js/vis-timeline/vis-timeline-graph2d.min.js"></script>
    <link rel="stylesheet" href="/admin/assets/css/vis-timeline/vis-timeline-graph2d.css">
    <script>
        $(document).ready(function () {
            let container = document.getElementById('timeline');
            let arr = []
            let id = {!! json_encode($id) !!}
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
            let selected = "{{$dates[0]}}"
            let timeline = new vis.Timeline(container, items, options);
            timeline.setSelection([selected]);
            timeline.on('select', function (properties) {
                if (properties.items.length > 0) {
                    selected = properties.items[0]
                    let url = 'id=' + id[selected]
                    $('#index-table').attr('data-menu', 'sample?' + url)
                    $('.btn-show').data('id', selected)
                    dataTable.ajax.url('/ajax_sample?' + url).load();
                }
                if (properties.items.length == 0) {
                    timeline.setSelection([selected]);
                }
            });
            timeline.setWindow('{{date('Y-m-d',strtotime($dates[0].'-1 month'))}}', '{{$dates[0]}} 23:59:59', {animation: true});
            $('.btn-download').click(function () {
                $('#date-download').val(selected)
                $('#id-download').val(id[selected])
                $('#form-download').submit()
            })
        });
    </script>
@endpush
