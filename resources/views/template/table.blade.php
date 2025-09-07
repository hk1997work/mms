<table id="{{$id??'index'}}-table" data-menu="{{$menu}}" class="table table-hover {{$class??''}}">
    <thead>
    <tr>
        @foreach($fields as $field)
            <th>{{$field}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @if(isset($items))
        @foreach($items as $item)
            <tr>
                @foreach($item->toArray() as $value)
                    <td>{{$value}}</td>
                @endforeach
            </tr>
        @endforeach
    @endif
    </tbody>
</table>