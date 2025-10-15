<div class="col-{{$tmp_col??'12'}} div-{{$tmp_name}}">
    <div class="form-label mt-3 mb-2">{{$tmp_label}}</div>
    <select name="{{$tmp_name}}" class="form-control form-select">
        <option value="" selected disabled>请选择...</option>
        @foreach($tmp_items as $tmp_item1)
            @foreach($tmp_item1->children as $tmp_item2)
                <optgroup label="{{$tmp_item2[$tmp_field1]}}">
                    @foreach($tmp_item2->children as $tmp_item3)
                        <option value="{{$tmp_item3[$tmp_value]}}" {{isset($tmp_selected, $tmp_validate) && $tmp_selected == $tmp_item3[$tmp_validate]?'selected':''}}>{{$tmp_item3[$tmp_field1]}}-{{$tmp_item3[$tmp_field2]}}</option>
                    @endforeach
                </optgroup>
            @endforeach
        @endforeach
    </select>
</div>