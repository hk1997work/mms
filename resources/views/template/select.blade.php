<div class="col-{{$tmp_col??'12'}} div-{{$tmp_name}}">
    <div class="sidebar-heading mt-3 mb-2">{{$tmp_label}}</div>
    <select name="{{$tmp_name}}" class="form-control form-select">
        <option value="" selected disabled>请选择...</option>
        @if(isset($tmp_items))
            @foreach($tmp_items as $tmp_item)
                <option value='{{$tmp_item[$tmp_value]}}' {{isset($tmp_selected, $tmp_validate) && $tmp_selected == $tmp_item[$tmp_validate]?'selected':''}}>{{$tmp_item[$tmp_field]}}</option>
            @endforeach
        @else
            <option value="1" {{($tmp_selected ?? null)=='1'?'selected':''}}>是</option>
            <option value="0" {{($tmp_selected ?? null)=='0'?'selected':''}}>否</option>
        @endif
    </select>
</div>