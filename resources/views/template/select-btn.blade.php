<div class="col-{{$col??'12'}} div-{{$name}}">
    <div class="sidebar-heading mt-3 mb-2">{{$label}}</div>
    <div class="input-group">
        <select name="{{$name}}" class="form-control form-select" {{$state??''}}>
            <option value="" selected disabled>请选择...</option>
            @if(isset($items))
                @foreach($items as $item)
                    <option value='{{$item[$value]}}' {{isset($selected, $validate) && $selected == $item[$validate]?'selected':''}}>{{$item[$field]}}</option>
                @endforeach
            @endif
        </select>
        <span class="btn btn-outline-secondary btn-add {{$class??''}}" data-pos='right' data-menu='{{$menu}}' hidden>增加</span>
    </div>
</div>