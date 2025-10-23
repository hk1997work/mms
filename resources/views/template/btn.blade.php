@php
    $tmp_id=isset($tmp_id)?"data-id=$tmp_id":'';
    # $tmp_class
    $tmp_label=$tmp_label??'确 定';
    $tmp_color=$tmp_color??'primary';
@endphp

<button class="btn btn-outline-{{$tmp_color}} sidebar-url {{$tmp_class}}" {{$tmp_id}}>{{$tmp_label}}</button>