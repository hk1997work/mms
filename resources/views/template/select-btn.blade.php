@php
    # $tmp_menu
    $tmp_id=($tmp_id??'')?"data-id=$tmp_id":'';
    # $tmp_name
    $tmp_class=$tmp_class??'';
    # $tmp_label
    # $tmp_items
    # $tmp_selected
    # $tmp_validate
    # $tmp_value
    # $tmp_field
    $tmp_reload=($tmp_reload??'')?"data-reload=$tmp_reload":'';
    $tmp_col=$tmp_col??'12';
    $tmp_show=$tmp_selected??'hidden';
@endphp

<div class="col-{{$tmp_col}} div-{{$tmp_name}}">
    <div class="form-label mt-3 mb-2">{!! $tmp_label !!}</div>
    <div class="input-group">
        <select name="{{$tmp_name}}" class="form-control form-select">
            <option value="" selected disabled>请选择...</option>
            @if(isset($tmp_items))
                @foreach($tmp_items as $tmp_item)
                    <option value='{{$tmp_item[$tmp_value]}}' {{isset($tmp_selected,$tmp_validate)&&$tmp_selected==$tmp_item[$tmp_validate]?'selected':''}}>{{$tmp_item[$tmp_field]}}</option>
                @endforeach
            @endif
        </select>
        <span class="btn btn-outline-secondary btn-add {{$tmp_class}}" data-pos='right' data-menu='{{$tmp_menu}}' {{$tmp_id}} {{$tmp_reload}} {{$tmp_show}}>增加</span>
    </div>
</div>