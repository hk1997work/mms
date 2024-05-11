@extends('layout.index')
@section('content_btn')
    <li class="nav-item"><a class="nav-link btn-download" href="#">下载</a></li>
    <form id="form-download" action="/cyclical" method="post">
        {{csrf_field()}}
        <input type="hidden" id="date-download" name="date" value="{{date('Y-m')}}">
    </form>
@endsection
@section('content_table')
    <div class="btn-group mb-3">
        <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-3" role="tablist">
            @foreach($types->where('date',date('Y-m')) as $type)
                <li><a @if($loop->index==0) class="active" @endif data-url="type={{$type->unit4}}&department={{$type->unit2}}&date={{date('Y-m')}}" href="#">{{$type->unit2}}{{$type->unit4}}</a></li>
            @endforeach
        </ul>
    </div>
    <table id="index-table" data-menu="cyclical?type={{$types->where('date',date('Y-m'))->first()->unit4}}&department={{$types->where('date',date('Y-m'))->first()->unit2}}&date={{date('Y-m')}}"
           class="table table-hover mb-0 unsorted nocheck tl-100">
        <thead>
        <tr>
            <th>序号</th>
            <th>岗位</th>
            <th>器具名称</th>
            <th>规格型号</th>
            <th>出厂编号</th>
            <th>测量范围</th>
            <th>精确度</th>
            <th>生产厂家</th>
            <th>检定日期</th>
            <th>有效期</th>
            <th>检定周期</th>
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
            let btn = []
            @foreach($types as $type)
            @if($loop->first || $types[$loop->index]->date != $types[$loop->index-1]->date)
            arr.push(
                {
                    id: '{{ $type->date }}',
                    content: '{{ $type->date }}',
                    start: "{{ $type->date }}-01 00:00:00",
                    end: "{{date('Y-m-t',strtotime($type->date))}} 23:59:59",
                }
            )
            @endif
            btn.push(
                {
                    date: '{{$type->date}}',
                    type: '{{$type->unit4}}',
                    department: '{{$type->unit2}}',
                }
            )
            @endforeach
            let items = new vis.DataSet(arr);
            let options = {
                height: '100px',
                stack: false,
                min: "{{$types->first()->date}}-01 00:00:00",
                max: "{{date('Y-m-t',strtotime($types->last()->date))}} 23:59:59",
            };
            let selected = "{{date('Y-m')}}"
            let timeline = new vis.Timeline(container, items, options);
            timeline.setSelection([selected]);
            timeline.on('select', function (properties) {
                if (properties.items.length > 0) {
                    selected = properties.items[0]
                    $('.button-nav').html('')
                    $.each(btn.filter(item => item.date == selected), function (key, value) {
                        let url = 'type=' + value['type'] + '&department=' + value['department'] + '&date=' + selected
                        if (key == 0) {
                            $('.button-nav').append('<li><a class="active" data-url="' + url + '" href="#">' + value['department'] + value['type'] + '</a></li>')
                            $('#index-table').attr('data-menu', 'cyclical?' + url)
                            dataTable.ajax.url('/ajax_cyclical?' + url).load();
                        } else {
                            $('.button-nav').append('<li><a data-url="' + url + '" href="#">' + value['department'] + value['type'] + '</a></li>')
                        }
                    });
                }
                if (properties.items.length == 0) {
                    timeline.setSelection([selected]);
                }
            });
            timeline.setWindow('{{now()->startOfMonth()}}', '{{now()->startOfMonth()->addYear()}}', {animation: true});
            $('.button-nav').on('click', 'a', function () {
                $('.button-nav a').removeClass('active')
                $(this).addClass('active')
                dataTable.ajax.url('/ajax_cyclical?' + $(this).data('url')).load();
            })
            $('.btn-download').click(function () {
                $('#date-download').val(selected)
                $('#form-download').submit()
            })
        });
    </script>
@endpush
