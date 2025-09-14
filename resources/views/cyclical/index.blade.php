@extends('layout.index')
@section('content_btn')
    @include('template.dropdown',['tmp_label'=>'','tmp_items'=>[],'tmp_href_id'=>'','tmp_field'=>''])
    @include('template.nav-btn',['tmp_label'=>'下载','tmp_menu'=>'cyclical','tmp_class'=>'submit-download'])
    <form id="form-download" action="/cyclical" method="post">
        {{csrf_field()}}
        <input type="hidden" id="date-download" name="date">
    </form>
@endsection
@section('content_table')
    @include('template.table',['tmp_menu'=>'cyclical?id='.$units->where('date',date('Y-m'))->first()->position4.'&date='.date('Y-m'),'tmp_fields'=>['序号','岗位','器具名称','规格型号','出厂编号','测量范围','精确度','生产厂家','检定日期','有效期','检定周期','备注'],'tmp_class'=>' table-tree table-all table-unselect'])
    <div id="timeline"></div>
@endsection
@push('page-js-after-1')
    <script>
        $(document).ready(function () {
            let container = document.getElementById('timeline');
            let arr = []
            let btn = []
            @foreach($units as $unit)
            @if($loop->first || $units[$loop->index]->date != $units[$loop->index-1]->date)
            arr.push(
                {
                    id: '{{ $unit->date }}',
                    content: '{{ $unit->date }}',
                    start: "{{ $unit->date }}-01 00:00:00",
                    end: "{{date('Y-m-t',strtotime($unit->date))}} 23:59:59",
                }
            )
            @endif
            btn.push(
                {
                    date: '{{$unit->date}}',
                    unit: '{{$unit->position4}}',
                }
            )
            @endforeach
            let items = new vis.DataSet(arr);
            let options = {
                height: '100px',
                stack: false,
                min: "{{$units->first()->date}}-01 00:00:00",
                max: "{{date('Y-m-t',strtotime($units->last()->date))}} 23:59:59",
            };
            let timeline = new vis.Timeline(container, items, options);
            let selected = "{{date('Y-m')}}"
            timeline.setSelection([selected]);
            timeline.setWindow('{{now()->startOfMonth()}}', '{{now()->startOfMonth()->addYear()}}', {animation: true});
            timeline.on('select', function (properties) {
                if (properties.items.length > 0) {
                    selected = properties.items[0]
                    $('.dropdown-menu-end').html('')
                    $.each(btn.filter(item => item.date == selected), function (key, value) {
                        let url = 'id=' + value['unit'] + '&date=' + selected
                        if (key == 0) {
                            $('.nav').find('.dropdown-toggle').text(value['unit'])
                            $('#index-table').attr('data-menu', 'cyclical?' + url)
                            if (properties.event) {
                                dataTable.ajax.url('/ajax_cyclical?' + url).load();
                            }
                        }
                        $('.dropdown-menu-end').append('<li><a class="dropdown-item" data-url="' + url + '" href="#">' + value['unit'] + '</a></li>')
                    });
                }
                if (properties.items.length == 0) {
                    timeline.setSelection([selected]);
                }
            });
            timeline.emit('select', {items: [selected]});
            $('.dropdown-menu-end').on('click', 'a', function () {
                $('.nav').find('.dropdown-toggle').text($(this).text())
                dataTable.ajax.url('/ajax_cyclical?' + $(this).data('url')).load();
            })
            $('.submit-download').click(function () {
                $('#date-download').val(selected)
                $('#form-download').submit()
            })
        });
    </script>
@endpush
