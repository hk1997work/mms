<div class="col-{{$tmp_col??'12'}} div-{{$tmp_name}}">
    <div class="sidebar-heading mt-3 mb-2">{{$tmp_label}}</div>
    <div class="input-group">
        <select name="{{$tmp_name}}" class="form-control form-select" {{$tmp_state??''}}>
            <option value="" selected disabled>请选择...</option>
            @if(isset($tmp_items))
                @foreach($tmp_items as $tmp_item)
                    <option value='{{$tmp_item[$tmp_value]}}' {{isset($tmp_selected, $tmp_validate) && $tmp_selected == $tmp_item[$tmp_validate]?'selected':''}}>{{$tmp_item[$tmp_field]}}</option>
                @endforeach
            @endif
        </select>
        <span class="btn btn-outline-secondary btn-add {{$tmp_class??''}}" data-pos='right' data-menu='{{$tmp_menu}}' hidden>增加</span>
    </div>
</div>