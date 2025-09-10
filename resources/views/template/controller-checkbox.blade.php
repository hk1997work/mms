<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
    <div class="mt-3 mb-2">{{$tmp_label}}</div>
    @if(isset($tmp_input))
        <input type="text" class="form-control w-100" name="{{$tmp_name}}" id="{{$tmp_name}}" disabled>
    @else
        <select name="{{$tmp_name}}" class="selectpicker w-100" multiple title="请选择..." required>
            @foreach($tmp_items as $tmp_item)
                <option {{$tmp_selected||$loop->first?'selected':''}}>{{$tmp_item}}</option>
            @endforeach
        </select>
    @endif
    @if(isset($tmp_check))
        <div class="mt-2">
            <input type="checkbox" name="check_{{str_replace('[]','',$tmp_name)}}" id="check_{{str_replace('[]','',$tmp_name)}}">
            <label for="check_{{str_replace('[]','',$tmp_name)}}">{{$tmp_check}}</label>
        </div>
    @endif
</div>