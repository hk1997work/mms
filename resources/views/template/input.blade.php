@php
    # $tmp_name
    $tmp_class=$tmp_class??'';
    # $tmp_label
    $tmp_value=isset($tmp_value)?"value=$tmp_value":'';
    $tmp_state=$tmp_state??'';
    $tmp_type=$tmp_type??'text';
    $tmp_col=$tmp_col??'12';
@endphp

<div class="col-{{$tmp_col}} div-{{$tmp_name}}">
    <label class="form-label mt-3 mb-2">{!! $tmp_label !!}</label>
    <input type="{{$tmp_type}}" name="{{$tmp_name}}" class="form-control {{$tmp_class}}" {{$tmp_value}} {{$tmp_state}}>
</div>