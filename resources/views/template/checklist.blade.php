<div role="tabpanel" class="tab-pane {{isset($show)?'show active':''}}" id="{{$name}}-tab" aria-labelledby="{{$name}}-btn">
    <div class="col-12 mt-3">
        @foreach($items as $item_1)
            <div class="mt-2">
                <input type="checkbox" class="btn-check" name="{{$name}}[]" id="{{$name}}{{$item_1->id}}" value="{{$item_1->id}}" data-level="1" @if($checked!=''&&$checked->contains($item_1)) checked @endif>
                <label class="btn btn-sm" for="{{$name}}{{$item_1->id}}">{{$item_1[$field]}}</label><br>
                @if($item_1->children)
                    @foreach($item_1->children as $item_2)
                        <div class="mt-2 ms-5">
                            <input type="checkbox" class="btn-check" name="{{$name}}[]" id="{{$name}}{{$item_2->id}}" value="{{$item_2->id}}" data-level="2" data-pid="{{$name}}{{$item_1->id}}" @if($checked!=''&&$checked->contains($item_2)) checked @endif>
                            <label class="btn btn-sm" for="{{$name}}{{$item_2->id}}">{{$item_2[$field]}}</label><br>
                            @if($item_2->children)
                                @foreach($item_2->children as $item_3)
                                    <div class="mt-2 ms-5">
                                        <input type="checkbox" class="btn-check" name="{{$name}}[]" id="{{$name}}{{$item_3->id}}" value="{{$item_3->id}}" data-level="3" data-pid="{{$name}}{{$item_2->id}}" data-ppid="{{$name}}{{$item_1->id}}"
                                               @if($checked!=''&&$checked->contains($item_3)) checked @endif>
                                        <label class="btn btn-sm" for="{{$name}}{{$item_3->id}}">{{$item_3[$field]}}</label><br>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
</div>