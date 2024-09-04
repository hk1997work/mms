@extends('layout.create')
@section('content_form')
    <div class="col-12 styled-checkbox ml-3 mb-5">
        <input type="checkbox" name="check_auto" id="check_auto" class="form-control">
        <label class="sidebar-heading mt-3 mb-2" for="check_auto">自动替换</label>
    </div>
    @foreach($certificates as $certificate)
        <div class="form-group row mb-5 mt-5">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3 div-group[{{$certificate['json']->id}}][tool_id] div-group[{{$certificate['json']->id}}][certificate_no]">
                <a href="/download_nanjing/{{$certificate['json']->zsbh}}?type=show" target="_blank" class="form-control-label text-info">{{$certificate['json']->name}}</a>
                <label class="form-control-label">{{$certificate['json']->xhgg}}</label>
                <select name="group[{{$certificate['json']->id}}][tool_id]" class="custom-select form-control @if($certificate['info']==0) is-invalid @endif" data-live-search="true">
                    <option title="请选择..." value="" selected disabled>请选择...</option>
                    @foreach($tools as $tool)
                        <option value="{{$tool->id}}" @if($certificate['info']&&$certificate['tool_id']==$tool->id) selected @endif>{{$tool->instrument}}--{{$tool->model}}</option>
                    @endforeach
                </select>
                <input type="hidden" name="group[{{$certificate['json']->id}}][path]" value="{{$certificate['path']}}">
                <input type="hidden" name="group[{{$certificate['json']->id}}][category]" value="{{mb_substr($certificate['category'],0,4)}}">
                <input type="hidden" name="group[{{$certificate['json']->id}}][certificate_no]" value="{{$certificate['json']->zsbh}}">
                <input type="hidden" name="group[{{$certificate['json']->id}}][certificate_name]" value="{{$certificate['json']->name}}">
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3 div-group[{{$certificate['json']->id}}][factory_id]">
                <label class="form-control-label"><span class="ff">{{$certificate['factory']}}</span></label>
                <div class="input-group">
                    <select name="group[{{$certificate['json']->id}}][factory_id]" class="custom-select form-control @if($certificate['info']==0) is-invalid @endif">
                        <option value='' selected disabled>请选择...</option>
                        @if($certificate['info'])
                            @foreach($certificate['factories'] as $factory)
                                <option value="{{$factory->id}}" @if($factory->id==$certificate['factory_id']) selected @endif>{{$factory->factory}}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="input-group-addon addon-primary btn-add factory_add" data-pos='right' data-menu='factory' data-cb="group[{{$certificate['json']->id}}][tool_id]" @if($certificate['info']) data-id="{{$certificate['tool_id']}}&name={{$certificate['factory']}}"
                          @else hidden @endif>增加</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3 div-group[{{$certificate['json']->id}}][number_id]">
                <label class="form-control-label"><span class="nn">{{isset($certificate['json']->ccbh)?$certificate['json']->ccbh=='/'?'':$certificate['json']->ccbh:''}}{{isset($certificate['json']->sbbh)?$certificate['json']->sbbh=='/'?'':$certificate['json']->sbbh:''}}</span></label>
                <div class="input-group">
                    <select name="group[{{$certificate['json']->id}}][number_id]" class="custom-select form-control @if($certificate['info']==0) is-invalid @endif">
                        <option value='' selected disabled>请选择...</option>
                        @if($certificate['info'])
                            @foreach($certificate['numbers'] as $number)
                                <option value="{{$number->id}}" @if($number->id==$certificate['number_id']) selected @endif>{{$number->number}}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="input-group-addon addon-primary btn-add number_add" data-pos='right' data-menu='number' data-cb="group[{{$certificate['json']->id}}][factory_id]"
                          @if($certificate['info']) data-id="{{$certificate['factory_id']}}&name={{isset($certificate['json']->ccbh)?$certificate['json']->ccbh=='/'?'':$certificate['json']->ccbh:''}}{{isset($certificate['json']->sbbh)?$certificate['json']->sbbh=='/'?'':$certificate['json']->sbbh:''}}"
                          @else hidden @endif>增加</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3 div-group[{{$certificate['json']->id}}][verification_date]">
                <label class="form-control-label">检定日期</label>
                <input type="text" name="group[{{$certificate['json']->id}}][verification_date]" class="form-control" value="{{$certificate['json']->jdrq}}">
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3 div-group[{{$certificate['json']->id}}][standard_id]">
                <label class="form-control-label">检定标准</label>
                <select name="group[{{$certificate['json']->id}}][standard_id][]" class="form-control" data-live-search="true" multiple>
                    @foreach($standards as $standard)
                        <option value="{{$standard->id}}" @if(in_array($standard->id,$certificate['standard'])) selected @endif>{{$standard->name1}}-{{Str::limit($standard->name2,35)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 mb-3 div-group[{{$certificate['json']->id}}][remark]">
                <label class="form-control-label">备注</label>
                <div class="input-group">
                    <input type="text" name="group[{{$certificate['json']->id}}][remark]" class="form-control">
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
