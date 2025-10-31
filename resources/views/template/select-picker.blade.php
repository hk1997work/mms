@php
    # $tmp_name
    # $tmp_label
    # $tmp_items
    # $tmp_selected
    # $tmp_validate
    # $tmp_value
    # tmp_title;
    # $tmp_field1
    # $tmp_field2
    $tmp_state=$tmp_state??'';
    $tmp_col=$tmp_col??'12';
@endphp

<div class="col-{{$tmp_col}} div-{{str_replace('[]','',$tmp_name)}}">
    <div class="form-label mt-3 mb-2">{!! $tmp_label !!}</div>
    <select name="{{$tmp_name}}" class="form-control form-select" data-live-search="true" title="请选择..." {{$tmp_state}}>
        @foreach($tmp_items as $tmp_item)
            @php
                $tmp_title=($tmp_title??'')?"title=$tmp_item[$tmp_title]":'';
            @endphp
            <option {{$tmp_title}} value='{{$tmp_item[$tmp_value]}}' {{isset($tmp_selected,$tmp_validate)&&((is_int($tmp_selected)||is_string($tmp_selected))?($tmp_selected==$tmp_item[$tmp_validate]):($tmp_selected->contains($tmp_item[$tmp_validate])))?'selected':''}}>
                {{$tmp_item[$tmp_field1]}}-{{Str::limit($tmp_item[$tmp_field2],35)}}</option>
        @endforeach
    </select>
</div>