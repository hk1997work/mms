<div class="col-{{$tmp_col??'12'}} div-{{$tmp_name}}">
    <div class="sidebar-heading mt-3 mb-2">{!! $tmp_label !!}</div>
    <div class="input-group">
        <input type="{{$tmp_type??'text'}}" name="{{$tmp_name}}" class="form-control" value="{{$tmp_value??''}}" {{$tmp_state??''}}>
        <span class="btn {{$tmp_class??''}}">{{$tmp_btn??'删除'}}</span>
    </div>
</div>