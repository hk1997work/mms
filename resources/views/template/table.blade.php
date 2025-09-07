<table id="{{$tmp_id??'index'}}-table" data-menu="{{$tmp_menu}}" class="table table-hover {{$tmp_class??''}}">
    <thead>
    <tr>
        @foreach($tmp_fields as $tmp_field)
            <th>{{$tmp_field}}</th>
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