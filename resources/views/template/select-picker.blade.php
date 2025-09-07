<div class="col-{{$tmp_col??'12'}} div-{{str_replace('[]','',$tmp_name)}}">
    <div class="sidebar-heading mt-3 mb-2">{{$tmp_label}}</div>
    <select name="{{$tmp_name}}" class="form-control form-select" data-live-search="true" title="请选择..." {{$tmp_state??''}}>
        @foreach($tmp_items as $tmp_item)
            <option {{isset($tmp_title)?"title=$tmp_item[$tmp_title]":''}} value='{{$tmp_item[$tmp_value]}}' {{isset($tmp_selected, $tmp_validate) &&$tmp_selected->contains($tmp_item[$tmp_validate])?'selected':''}}>{{$tmp_item[$tmp_field1]}}-{{Str::limit($tmp_item[$tmp_field2],35)}}</option>
        @endforeach
    </select>
</div>