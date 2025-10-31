@php
    # $tmp_name
    # $tmp_items
    # $tmp_field
    # $tmp_checked
     $tmp_active=($tmp_active??'')?'show active':'';
@endphp


<div role="tabpanel" class="tab-pane {{$tmp_active}}" id="{{$tmp_name}}-tab" aria-labelledby="{{$tmp_name}}-btn">
    <div class="col-12 mt-3">
        @foreach($tmp_items as $tmp_item_1)
            <div class="mt-2">
                <input type="checkbox" class="btn-check" name="{{$tmp_name}}[]" id="{{$tmp_name}}{{$tmp_item_1->id}}" value="{{$tmp_item_1->id}}" data-level="1" {{$tmp_checked!=''&&$tmp_checked->contains($tmp_item_1)?'checked':''}}>
                <label class="btn btn-sm" for="{{$tmp_name}}{{$tmp_item_1->id}}">{{$tmp_item_1[$tmp_field]}}</label><br>
                @if($tmp_item_1->children)
                    @foreach($tmp_item_1->children as $tmp_item_2)
                        <div class="mt-2 ms-5">
                            <input type="checkbox" class="btn-check" name="{{$tmp_name}}[]" id="{{$tmp_name}}{{$tmp_item_2->id}}" value="{{$tmp_item_2->id}}" data-level="2" data-pid="{{$tmp_name}}{{$tmp_item_1->id}}" {{$tmp_checked!=''&&$tmp_checked->contains($tmp_item_2)?'checked':''}}>
                            <label class="btn btn-sm" for="{{$tmp_name}}{{$tmp_item_2->id}}">{{$tmp_item_2[$tmp_field]}}</label><br>
                            @if($tmp_item_2->children)
                                @foreach($tmp_item_2->children as $tmp_item_3)
                                    <div class="mt-2 ms-5">
                                        <input type="checkbox" class="btn-check" name="{{$tmp_name}}[]" id="{{$tmp_name}}{{$tmp_item_3->id}}" value="{{$tmp_item_3->id}}" data-level="3" data-pid="{{$tmp_name}}{{$tmp_item_2->id}}"
                                               data-ppid="{{$tmp_name}}{{$tmp_item_1->id}}" {{$tmp_checked!=''&&$tmp_checked->contains($tmp_item_3)?'checked':''}}>
                                        <label class="btn btn-sm" for="{{$tmp_name}}{{$tmp_item_3->id}}">{{$tmp_item_3[$tmp_field]}}</label><br>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
            <hr>
        @endforeach
    </div>
</div>