<div class="col-{{$col??'12'}} div-{{$name}}">
    <div class="sidebar-heading mt-3 mb-2">{{$label}}</div>
    <select name="{{$name}}" class="form-control form-select" data-live-search="true" title="请选择..." {{$state??''}}>
        @foreach($items as $item)
            <option {{isset($title)?"title=$item[$title]":''}} value='{{$item[$value]}}' {{isset($selected, $validate) &&$selected->contains($item[$validate])?'selected':''}}>{{$item[$field1]}}-{{Str::limit($item[$field2],35)}}</option>
        @endforeach
    </select>
</div>