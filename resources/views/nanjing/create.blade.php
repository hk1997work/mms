@extends('layout.create')
@section('text_modal-title','批量录入')

@section('content_form')
    @foreach($arr as $a)
        <div class="form-group row mb-5 mt-5">
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 @if($a->info==0) has-danger @endif">
                <a href="http://58.213.156.66/cmiims/static/angular/views/plugs/viewer.html?id={{$a->pdfDzqzPath}}" id="tool_text{{$a->order}}" target="_blank" class="form-control-label text-info">{{$a->name}}</a>
                <label class="form-control-label" id="model_text{{$a->order}}">{{$a->xhgg}}</label>
                <select name="tool_id[{{$a->order}}]" id="tool_id{{$a->order}}" class="form-control selectpicker show-menu-arrow" data-live-search="true" onchange="load_info({{$a->order}})">
                    <option title="请选择..." value="" selected>请选择...</option>
                    @foreach($tools as $tool)
                        <option value="{{$tool->id}}" @if($a->info&&$a->tool_id==$tool->id) selected @endif>{{$tool->instrument}}--{{$tool->model}}</option>
                    @endforeach
                </select>
                <input type="hidden" name="certificate_no[{{$a->order}}]" id="certificate_no{{$a->order}}" value="{{$a->zsbh}}">
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 @if($a->info==0) has-danger @endif">
                <label class="form-control-label factory_text" id="factory_text{{$a->factory}}">{{$a->factory}}</label>
                <div class="input-group">
                    <select name="factory_id[{{$a->order}}]" id="factory_id{{$a->order}}" class="custom-select form-control" onchange="load_numbers({{$a->order}})">
                        @if($a->info)
                            <option value='' disabled>请选择...</option>
                            @foreach($a->factories as $factory)
                                <option value="{{$factory->id}}" @if($factory->id==$a->factory_id) selected @endif>{{$factory->factory}}</option>
                            @endforeach
                        @else
                            <option value="" selected>请选择...</option>
                        @endif
                    </select>
                    <span class="input-group-addon addon-primary" id="factory_id_btn{{$a->order}}" onclick="factory_id_click({{$a->order}})">增加</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 @if($a->info==0) has-danger @endif">
                <label class="form-control-label" id="number_text{{$a->order}}">{{isset($a->ccbh)?$a->ccbh=='/'?'':$a->ccbh:''}}{{isset($a->sbbh)?$a->sbbh=='/'?'':$a->sbbh:''}}</label>
                <div class="input-group">
                    <select name="number_id[{{$a->order}}]" id="number_id{{$a->order}}" class="custom-select form-control">
                        @if($a->info)
                            <option value='' disabled>请选择...</option>
                            @foreach($a->numbers as $number)
                                <option value="{{$number->id}}" @if($number->id==$a->number_id) selected @endif>{{$number->number}}</option>
                            @endforeach
                        @else
                            <option value="" selected>请选择...</option>
                        @endif
                    </select>
                    <span class="input-group-addon addon-primary" id="number_id_btn{{$a->order}}" onclick="number_id_click({{$a->order}})">增加</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                <label class="form-control-label">检定日期</label>
                <input type="text" name="verification_date[{{$a->order}}]" id="verification_date{{$a->order}}" value="{{$a->jdrq}}" class="form-control datepicker">
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 has-danger">
                <label class="form-control-label">检定标准</label>
                <select name="standard_id[{{$a->order}}][]" id="standard_id{{$a->order}}" class="form-control selectpicker show-menu-arrow" data-live-search="true" multiple>
                    @foreach($standards as $standard)
                        <option value="{{$standard->id}}" @if($a->info&&in_array($standard->id,$a->standard)) selected @endif>{{$standard->name1}}-{{$standard->name2}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                <label class="form-control-label">备注</label>
                <div class="input-group">
                    <input type="text" name="remark[{{$a->order}}]" id="remark{{$a->order}}" class="form-control">
                    <span class="input-group-addon addon-orange" onclick="$(this).parent().parent().parent().remove()">删除</span>
                </div>
            </div>
            <input type="hidden" name="json[{{$a->order}}]" id="json{{$a->order}}" value="{{json_encode($a)}}">
        </div>
    @endforeach
    <input type="hidden" name="data" id="data" value="{{$data}}">
    <input type="hidden" name="idList" id="idList" value="{{$idList}}">
@endsection
<link rel="stylesheet" href="/admin/assets/css/bootstrap-select/bootstrap-select.min.css">

<script src="/admin/assets/vendors/js/datepicker/moment.min.js"></script>
<script src="/admin/assets/vendors/js/datepicker/daterangepicker.js"></script>
<script src="/admin/assets/vendors/js/bootstrap-select/bootstrap-select.min.js"></script>

<script src="/admin/assets/js/pages/nanjing.js"></script>

<script>
    $(".selectpicker").selectpicker();
    $(".datepicker").daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
</script>
