@extends('layout.create')
@section('text_modal-title','批量录入')

@section('content_form')
    @foreach($arr as $a)
        <div class="form-group row mb-5 mt-5">
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 @if($a['info']==0) has-danger @endif">
                <a href="/jiangsu/1?str={{str_replace('#','@',json_encode($a['json']))}}" id="tool_text{{$a['json']->zsId}}" target="_blank" class="form-control-label text-info">{{$a['json']->zsQjmc}}</a>
                <label class="form-control-label" id="model_text{{$a['json']->zsId}}">{{$a['json']->zsXhgg}}</label>
                <select name="tool_id[{{$a['json']->zsId}}]" id="tool_id{{$a['json']->zsId}}" class="form-control selectpicker show-menu-arrow" data-live-search="true" onchange="load_factories({{$a['json']->zsId}})">
                    <option title="请选择..." value="" selected disabled>请选择...</option>
                    @foreach($tools as $tool)
                        <option value="{{$tool->id}}" @if($a['info']&&$a['tool_id']==$tool->id) selected @endif>{{$tool->instrument}}--{{$tool->model}}</option>
                    @endforeach
                </select>
                <input type="hidden" name="path[{{$a['json']->zsId}}]" id="path[{{$a['json']->zsId}}]" value="{{$a['path']}}">
                <input type="hidden" name="category[{{$a['json']->zsId}}]" id="category[{{$a['json']->zsId}}]" value="{{mb_substr($a['category'],0,4)}}">
                <input type="hidden" name="certificate_no[{{$a['json']->zsId}}]" id="certificate_no{{$a['json']->zsId}}" value="{{$a['json']->zsZsh}}">
                <input type="hidden" name="certificate_name[{{$a['json']->zsId}}]" id="certificate_name{{$a['json']->zsId}}" value="{{$a['json']->zsQjmc}}">
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 @if($a['info']==0) has-danger @endif">
                <label class="form-control-label factory_text" id="factory_text{{$a['json']->zsZzc}}">{{$a['json']->zsZzc}}</label>
                <div class="input-group">
                    <select name="factory_id[{{$a['json']->zsId}}]" id="factory_id{{$a['json']->zsId}}" class="custom-select form-control" onchange="load_numbers({{$a['json']->zsId}})">
                        @if($a['info'])
                            <option value='' disabled>请选择...</option>
                            @foreach($a['factories'] as $factory)
                                <option value="{{$factory->id}}" @if($factory->id==$a['factory_id']) selected @endif>{{$factory->factory}}</option>
                            @endforeach
                        @else
                            <option value="" selected>请选择...</option>
                        @endif
                    </select>
                    <span class="input-group-addon addon-primary" id="factory_id_btn{{$a['json']->zsId}}" onclick="factory_id_click({{$a['json']->zsId}})">增加</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 @if($a['info']==0) has-danger @endif">
                <label class="form-control-label" id="number_text{{$a['json']->zsId}}">{{isset($a['json']->zsCcbh)?$a['json']->zsCcbh=='/'?'':$a['json']->zsCcbh:''}}{{isset($a['json']->zsSbbh)?$a['json']->zsSbbh=='/'?'':$a['json']->zsSbbh:''}}</label>
                <div class="input-group">
                    @if($a['info']==0)
                        <input type='text' name='number_id[{{$a['json']->zsId}}]' id='number_id{{$a['json']->zsId}}' class='form-control' value="{{isset($a['json']->zsCcbh)?$a['json']->zsCcbh=='/'?'':$a['json']->zsCcbh:''}}{{isset($a['json']->zsSbbh)?$a['json']->zsSbbh=='/'?'':$a['json']->zsSbbh:''}}">
                        <span class='input-group-addon addon-orange' id='number_id_btn{{$a['json']->zsId}}' onclick='number_id_click("{{$a['json']->zsId}}")'>返回</span>
                    @else
                        <select name="number_id[{{$a['json']->zsId}}]" id="number_id{{$a['json']->zsId}}" class="custom-select form-control">
                            @if($a['info'])
                                <option value='' disabled>请选择...</option>
                                @foreach($a['numbers'] as $number)
                                    <option value="{{$number->id}}" @if($number->id==$a['number_id']) selected @endif>{{$number->number}}</option>
                                @endforeach
                            @else
                                <option value="" selected>请选择...</option>
                            @endif
                        </select>
                        <span class="input-group-addon addon-primary" id="number_id_btn{{$a['json']->zsId}}" onclick="number_id_click({{$a['json']->zsId}})">增加</span>
                    @endif
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                <label class="form-control-label">检定日期</label>
                <input type="text" name="verification_date[{{$a['json']->zsId}}]" id="verification_date{{$a['json']->zsId}}" value="{{$a['json']->zsJdrq}}" class="form-control datepicker">
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                <label class="form-control-label">检定标准</label>
                <select name="standard_id[{{$a['json']->zsId}}][]" id="standard_id{{$a['json']->zsId}}" class="form-control selectpicker show-menu-arrow" data-live-search="true" multiple>
                    @foreach($standards as $standard)
                        <option value="{{$standard->id}}" @if(in_array($standard->id,$a['standard'])) selected @endif>{{$standard->name1}}-{{$standard->name2}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                <label class="form-control-label">备注</label>
                <div class="input-group">
                    <input type="text" name="remark[{{$a['json']->zsId}}]" id="remark{{$a['json']->zsId}}" class="form-control">
                    <span class="input-group-addon addon-orange" onclick="$(this).parent().parent().parent().remove()">删除</span>
                </div>
            </div>
        </div>
    @endforeach
@endsection
<link rel="stylesheet" href="/admin/assets/css/bootstrap-select/bootstrap-select.min.css">

<script src="/admin/assets/vendors/js/datepicker/moment.min.js"></script>
<script src="/admin/assets/vendors/js/datepicker/daterangepicker.js"></script>
<script src="/admin/assets/vendors/js/bootstrap-select/bootstrap-select.min.js"></script>

<script src="/admin/assets/js/pages/jiangsu.js"></script>

<script>
    $(".selectpicker").selectpicker();
    $(".datepicker").daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    $(document).ready(function () {
        $('.modal-body').on('change', 'select', function () {
            if ($(this).val() == '') {
                $(this).parent().parent().addClass('has-danger');
            } else {
                $(this).parent().parent().removeClass('has-danger');
            }
        });
    });
</script>
