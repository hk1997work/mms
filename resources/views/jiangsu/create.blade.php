@extends('layout.create')
@section('content_form')
    @foreach($certificates as $certificate)
        <div class="form-group row mb-5 mt-5">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3 div-group[{{$certificate['json']->zsId}}][tool_id] div-group[{{$certificate['json']->zsId}}][certificate_no]">
                <a href="/download_jiangsu/{{$certificate['json']->zsZsh}}" target="_blank" class="form-control-label text-info">{{$certificate['json']->zsQjmc}}</a>
                <label class="form-control-label">{{$certificate['json']->zsXhgg}}</label>
                <select name="group[{{$certificate['json']->zsId}}][tool_id]" class="custom-select form-control @if($certificate['info']==0) is-invalid @endif" data-live-search="true">
                    <option title="请选择..." value="" selected disabled>请选择...</option>
                    @foreach($tools as $tool)
                        <option title="{{$tool->instrument}}" value="{{$tool->id}}" @if($certificate['info']&&$certificate['tool_id']==$tool->id) selected @endif>{{$tool->instrument}}--{{$tool->model}}</option>
                    @endforeach
                </select>
                <input type="hidden" name="group[{{$certificate['json']->zsId}}][path]" value="{{$certificate['path']}}">
                <input type="hidden" name="group[{{$certificate['json']->zsId}}][category]" value="{{mb_substr($certificate['category'],0,4)}}">
                <input type="hidden" name="group[{{$certificate['json']->zsId}}][certificate_no]" value="{{$certificate['json']->zsZsh}}">
                <input type="hidden" name="group[{{$certificate['json']->zsId}}][certificate_name]" value="{{$certificate['json']->zsQjmc}}">
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3 div-group[{{$certificate['json']->zsId}}][factory_id]">
                <label class="form-control-label ff">{{$certificate['json']->zsZzc}}</label>
                <div class="input-group">
                    <select name="group[{{$certificate['json']->zsId}}][factory_id]" class="custom-select form-control @if($certificate['info']==0) is-invalid @endif">
                        <option value='' selected disabled>请选择...</option>
                        @if($certificate['info'])
                            @foreach($certificate['factories'] as $factory)
                                <option value="{{$factory->id}}" @if($factory->id==$certificate['factory_id']) selected @endif>{{$factory->factory}}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="input-group-addon addon-primary btn-add factory_add" data-pos='right' data-menu='factory' data-cb="group[{{$certificate['json']->zsId}}][tool_id]" @if($certificate['info']) data-id="{{$certificate['tool_id']}}" @else hidden @endif>增加</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3 div-group[{{$certificate['json']->zsId}}][number_id]">
                <label class="form-control-label nn">{{isset($certificate['json']->zsCcbh)?$certificate['json']->zsCcbh=='/'?'':$certificate['json']->zsCcbh:''}}{{isset($certificate['json']->zsSbbh)?$certificate['json']->zsSbbh=='/'?'':$certificate['json']->zsSbbh:''}}</label>
                <div class="input-group">
                    <select name="group[{{$certificate['json']->zsId}}][number_id]" class="custom-select form-control @if($certificate['info']==0) is-invalid @endif">
                        <option value='' selected disabled>请选择...</option>
                        @if($certificate['info'])
                            @foreach($certificate['numbers'] as $number)
                                <option value="{{$number->id}}" @if($number->id==$certificate['number_id']) selected @endif>{{$number->number}}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="input-group-addon addon-primary btn-add number_add" data-pos='right' data-menu='number' data-cb="group[{{$certificate['json']->zsId}}][factory_id]" @if($certificate['info']) data-id="{{$certificate['factory_id']}}" @else hidden @endif>增加</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3 div-group[{{$certificate['json']->zsId}}][verification_date]">
                <label class="form-control-label">检定日期</label>
                <input type="text" name="group[{{$certificate['json']->zsId}}][verification_date]" class="form-control" value="{{$certificate['json']->zsJdrq}}">
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3 div-group[{{$certificate['json']->zsId}}][standard_id]">
                <label class="form-control-label">检定标准</label>
                <select name="group[{{$certificate['json']->zsId}}][standard_id][]" class="form-control" data-live-search="true" multiple>
                    @foreach($standards as $standard)
                        <option value="{{$standard->id}}" @if(in_array($standard->id,$certificate['standard'])) selected @endif>{{$standard->name2}}-{{Str::limit($standard->name1,35)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3 div-group[{{$certificate['json']->zsId}}][remark]">
                <label class="form-control-label">备注</label>
                <div class="input-group">
                    <input type="text" name="group[{{$certificate['json']->zsId}}][remark]" class="form-control">
                    <span class="input-group-addon addon-orange group-delete">删除</span>
                </div>
            </div>
        </div>
    @endforeach
    <script>
        $('[name^="group["][name$="][tool_id]"],[name^="group["][name$="][standard_id][]"]').selectpicker();
        $('[name^="group["][name$="][verification_date]"]').daterangepicker({
            singleDatePicker: true,
            container: '[name^="group["][name$="][verification_date]"]',
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    </script>
@endsection
