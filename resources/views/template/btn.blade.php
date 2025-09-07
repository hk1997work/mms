<div class="position-fixed bottom-0 end-0 p-3">
    @if(isset($tmp_class))
        <button class="btn btn-outline-{{$tmp_color??'primary'}} sidebar-url {{$tmp_class}}">{{$tmp_label??'确 定'}}</button>
    @endif
    <button class="btn btn-outline-secondary sidebar-close">返 回</button>
</div>