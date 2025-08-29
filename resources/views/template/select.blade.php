<div class="col-12 div-{{$name}}">
    <div class="sidebar-heading mt-3 mb-2">{{$label}}</div>
    <select name="{{$name}}" class="form-control form-select">
        <option value="" selected disabled>请选择...</option>
        @if(isset($items))
            @foreach($items as $item)
                <option value='{{$item[$value]}}'{{isset($selected, $valid) && $selected == $item[$valid]?'selected':''}}>{{$item[$field]}}</option>
            @endforeach
        @else
            <option value="1" {{($selected ?? null)=='1'?'selected':''}}>是</option>
            <option value="0" {{($selected ?? null)=='0'?'selected':''}}>否</option>
        @endif
    </select>
</div>