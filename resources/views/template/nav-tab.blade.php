@php
    # $tmp_name
    # $tmp_label
    $tmp_state=$tmp_state??'';
    $tmp_active=($tmp_active??'')?'active':'';
@endphp

<li class="nav-item">
    <button class="nav-link {{$tmp_active}}" data-bs-toggle="tab" data-bs-target="#{{$tmp_name}}-tab" id="{{$tmp_name}}-btn" {{$tmp_state}}>{{$tmp_label}}</button>
</li>