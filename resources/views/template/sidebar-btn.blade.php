@php
    # $tmp_class
    $tmp_label=$tmp_label??'确 定';
    $tmp_color=$tmp_color??'primary';
@endphp

<div class="position-fixed bottom-0 end-0 p-3">
    @if(isset($tmp_class))
        <button class="btn btn-outline-{{$tmp_color}} sidebar-url {{$tmp_class}}">{{$tmp_label}}</button>
    @endif
    <button class="btn btn-outline-secondary sidebar-close" type="button">返 回</button>
</div>