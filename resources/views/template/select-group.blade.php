<div class="col-{{$col??'12'}} div-{{$name}}">
    <div class="sidebar-heading mt-3 mb-2">{{$label}}</div>
    <select name="{{$name}}" class="form-control form-select">
        <option value="" selected disabled>请选择...</option>
        @foreach($items as $item1)
            @foreach($item1->children as $item2)
                <optgroup label="{{$item2[$field1]}}">
                    @foreach($item2->children as $item3)
                        <option value="{{$item3[$value]}}" {{isset($selected, $validate) && $selected == $item3[$validate]?'selected':''}}>{{$item3[$field1]}}-{{$item3[$field2]}}</option>
                    @endforeach
                </optgroup>
            @endforeach
        @endforeach
    </select>
</div>