@if(isset($class))
    <button class="btn btn-outline-{{$color??'primary'}} sidebar-url {{$class}}">{{$label??'确 定'}}</button>
@endif
<button class="btn btn-outline-secondary sidebar-close">返 回</button>