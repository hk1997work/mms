@php
    # $tmp_name
    $tmp_class=$tmp_class??'';
    # $tmp_label
    $tmp_col=$tmp_col??'12';
@endphp

<div class="col-{{$tmp_col}} div-{{$tmp_name}}">
    <div class="form-label mt-3 mb-2">{!! $tmp_label !!}</div>
    <div class="input-group">
        <input type="text" name="{{$tmp_name}}" class="form-control">
        <span class="btn {{$tmp_class}}">删除</span>
    </div>
</div>