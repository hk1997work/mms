@php
    # $tmp_menu
    # $tmp_headers
    $tmp_id=$tmp_id??'index';
    $tmp_class=$tmp_class??'';
    # $tmp_items
@endphp

<table id="{{$tmp_id}}-table" data-menu="{{$tmp_menu}}" class="table table-hover {{$tmp_class}}">
    <thead>
    <tr>
        @foreach($tmp_headers as $tmp_header)
            <th>{{$tmp_header}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @if(isset($tmp_items))
        @foreach($tmp_items as $tmp_item)
            <tr>
                @foreach($tmp_item->toArray() as $tmp_value)
                    <td>{{$tmp_value}}</td>
                @endforeach
            </tr>
        @endforeach
    @endif
    </tbody>
</table>