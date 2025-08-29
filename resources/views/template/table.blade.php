<table id="{{$id??'index'}}-table" data-menu="{{$menu}}" class="table table-hover {{$class??''}}">
    <thead>
    <tr>
        <th></th>
        @foreach($fields as $field)
            <th>{{$field}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody></tbody>
</table>