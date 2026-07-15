@php
    # $tmp_menu
    $tmp_id=($tmp_id??'')?"data-id=$tmp_id":'';
    # $tmp_class
    # $tmp_label
    $tmp_url=($tmp_url??'')?"data-url=$tmp_url":'';
    $tmp_type=(isset($tmp_type)&&$tmp_type!==null)?"data-type=$tmp_type":'';
    $tmp_pos=$tmp_pos??'right';
@endphp

<li class="nav-item">
    <a class="nav-link {{$tmp_class}}" data-menu="{{$tmp_menu}}" href="#" data-pos="{{$tmp_pos}}" {{$tmp_type}} {{$tmp_url}} {{$tmp_id}}>{{$tmp_label}}</a>
</li>
