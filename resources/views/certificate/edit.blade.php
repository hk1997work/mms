@extends('layout.show')
@section('content_title')
    @if($certificate->valid)
        @if($certificate->position1=='备用')
            @include('template.nav-tab',['name'=>'apply','label'=>'使用','active'=>true])
        @else
            @if($spares->count()!=0)
                @include('template.nav-tab',['name'=>'spare','label'=>'备用','active'=>true])
            @endif
            @include('template.nav-tab',['name'=>'replace','label'=>'更新','active'=>$spares->count()==0])
        @endif
    @endif
    @include('template.nav-tab',['name'=>'edit','label'=>'修改','active'=>$certificate->valid==0])
@endsection
@section('content_form')
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane @if($certificate->valid==0) show active @endif" id="edit-tab" aria-labelledby="edit-btn">
            <form action="" onsubmit="return false;">
                {{method_field("put")}}
                {{csrf_field()}}
                <div class="row">
                    <input type="hidden" name="type" value="edit">
                    <input type="hidden" name="position_id" value="{{$certificate->position_id1}}">
                    <input type="hidden" name="tool_id" value="{{$certificate->tool_id}}">
                    <input type="hidden" name="factory_id" value="{{$certificate->factory_id}}">
                    <input type="hidden" name="number_id" value="{{$certificate->number_id}}">
                    @include('template.input',['name'=>'position_id','label'=>'岗位','value'=>$certificate->position1,'col'=>'6','state'=>'disabled'])
                    @include('template.input',['name'=>'sn','label'=>'序号','value'=>$certificate->sn,'col'=>'6','state'=>'readonly'])
                    @include('template.input',['name'=>'tool_id','label'=>'器具名称','value'=>$certificate->instrument,'col'=>'6','state'=>'disabled'])
                    @include('template.input',['name'=>'model','label'=>'规格型号','value'=>$certificate->model,'col'=>'6','state'=>'readonly'])
                    @include('template.input',['name'=>'factory_id','label'=>'生产厂家','value'=>$certificate->factory,'col'=>'6','state'=>'disabled'])
                    @include('template.input',['name'=>'limit','label'=>'测量范围','value'=>$certificate->limit,'col'=>'6','state'=>'readonly'])
                    @include('template.input',['name'=>'number_id','label'=>'出厂编号','value'=>$certificate->number,'col'=>'6','state'=>'disabled'])
                    @include('template.input',['name'=>'accuracy','label'=>'精确度','value'=>$certificate->accuracy,'col'=>'6','state'=>'readonly'])
                    @include('template.select',['name'=>'department_id','label'=>'检定部门','selected'=>$certificate->department_id,'items'=>$departments,'value'=>'id','validate'=>'id','field'=>'name','col'=>'6'])
                    @include('template.input',['name'=>'cycle_id','label'=>'检定周期','value'=>$certificate->cycle,'col'=>'6','state'=>'readonly'])
                    @include('template.select',['name'=>'category_id','label'=>'证书类型','selected'=>$certificate->category_id,'items'=>$categories,'value'=>'id','validate'=>'id','field'=>'name','col'=>'6'])
                    @include('template.input',['name'=>'abc_id','label'=>'ABC','value'=>$certificate->abc,'col'=>'6','state'=>'readonly'])
                    @include('template.input',['name'=>'verification_date','label'=>'检定日期','value'=>$certificate->verification_date,'col'=>'6'])
                    @include('template.input',['name'=>'validity_date','label'=>'有效期','value'=>$certificate->validity_date,'col'=>'6','state'=>'readonly'])
                    @include('template.input',['name'=>'certificate_no','label'=>'统一编号','value'=>$certificate->certificate_no,'col'=>'6'])
                    @include('template.input',['name'=>'start','label'=>'启用时间','value'=>$certificate->start,'col'=>'6','state'=>'readonly'])
                    @include('template.select-picker',['name'=>'standard_id[]','label'=>'检定标准','selected'=>$certificate->standards,'items'=>$standards,'value'=>'id','validate'=>'id','field1'=>'name2','field2'=>'name1','state'=>'multiple','col'=>'6'])
                    @include('template.input',['name'=>'times','label'=>'检定次数','value'=>$certificate->times,'col'=>'6','state'=>'readonly'])
                    @include('template.input',['name'=>'certificate_name','label'=>'证书名称','value'=>$certificate->certificate_name,'col'=>'6'])
                    @include('template.input',['name'=>'plan','label'=>'检定计划','value'=>$certificate->plan,'col'=>'6','state'=>'readonly'])
                    @include('template.input',['name'=>'remark','label'=>'备注','value'=>$certificate->remark,'col'=>'6'])
                    @include('template.input',['name'=>'file_certificate','label'=>'上传证书','col'=>'6','state'=>'accept="application/pdf"','type'=>'file'])
                </div>
                @include('template.btn',['class'=>'submit-edit'])
            </form>
        </div>
        @if($certificate->valid)
            @if($certificate->position1=='备用')
                <div role="tabpanel" class="tab-pane show active" id="apply-tab" aria-labelledby="apply-btn">
                    <form action="" onsubmit="return false;">
                        {{method_field("put")}}
                        {{csrf_field()}}
                        <div class="row">
                            <input type="hidden" name="type" value="apply">
                            <input type="hidden" name="tool_id" value="{{$certificate->tool_id}}">
                            <input type="hidden" name="factory_id" value="{{$certificate->factory_id}}">
                            <input type="hidden" name="number_id" value="{{$certificate->number_id}}">
                            <input type="hidden" name="department_id" value="{{$certificate->department_id}}">
                            <input type="hidden" name="category_id" value="{{$certificate->category_id}}">
                            @foreach($certificate->standards as $standard)
                                <input type="hidden" name="standard_id[]" value="{{$standard->id}}">
                            @endforeach
                            @include('template.select-group',['name'=>'position_id','label'=>'岗位','items'=>$positions,'value'=>'id','field1'=>'name','field2'=>'code','col'=>'6'])
                            @include('template.input',['name'=>'sn','label'=>'序号','col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'tool_id','label'=>'器具名称','value'=>$certificate->instrument,'col'=>'6','state'=>'disabled'])
                            @include('template.input',['name'=>'model','label'=>'规格型号','value'=>$certificate->model,'col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'factory_id','label'=>'生产厂家','value'=>$certificate->factory,'col'=>'6','state'=>'disabled'])
                            @include('template.input',['name'=>'limit','label'=>'测量范围','value'=>$certificate->limit,'col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'number_id','label'=>'出厂编号','value'=>$certificate->number,'col'=>'6','state'=>'disabled'])
                            @include('template.input',['name'=>'accuracy','label'=>'精确度','value'=>$certificate->accuracy,'col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'department_id','label'=>'检定部门','value'=>$certificate->department,'col'=>'6','state'=>'disabled'])
                            @include('template.input',['name'=>'cycle_id','label'=>'检定周期','value'=>$certificate->cycle,'col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'category_id','label'=>'证书类型','value'=>$certificate->category,'col'=>'6','state'=>'disabled'])
                            @include('template.input',['name'=>'abc_id','label'=>'ABC','value'=>$certificate->abc,'col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'verification_date','label'=>'检定日期','value'=>$certificate->verification_date,'col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'validity_date','label'=>'有效期','value'=>$certificate->validity_date,'col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'certificate_no','label'=>'统一编号','value'=>$certificate->certificate_no,'col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'start','label'=>'启用时间','value'=>$certificate->start,'col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'standard_id','label'=>'检定标准','value'=>$certificate->standard,'col'=>'6','state'=>'disabled'])
                            @include('template.input',['name'=>'times','label'=>'检定次数','value'=>$certificate->times,'col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'certificate_name','label'=>'证书名称','value'=>$certificate->certificate_name,'col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'plan','label'=>'检定计划','value'=>$certificate->plan,'col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'remark','label'=>'备注','value'=>$certificate->remark,'col'=>'6'])
                            @include('template.input',['name'=>'file_certificate','label'=>'上传证书','col'=>'6','state'=>'accept="application/pdf"','type'=>'file'])
                        </div>
                        @include('template.btn',['class'=>'submit-edit'])
                    </form>
                </div>
            @else
                <div role="tabpanel" class="tab-pane @if($spares->count()==0) show active @endif" id="replace-tab" aria-labelledby="replace-btn">
                    <form action="" onsubmit="return false;">
                        {{method_field("put")}}
                        {{csrf_field()}}
                        <div class="row">
                            <input type="hidden" name="type" value="replace">
                            <input type="hidden" name="id" value="{{$certificate->id}}">
                            <input type="hidden" name="position_id" value="{{$certificate->position_id}}">
                            <input type="hidden" name="number" value="{{$certificate->number_id}}">
                            @include('template.select',['name'=>'cause','label'=>'损坏','col'=>'3'])
                            @include('template.input',['name'=>'position_id','label'=>'岗位','value'=>$certificate->position1,'col'=>'3','state'=>'disabled'])
                            @include('template.input',['name'=>'sn','label'=>'序号','value'=>$certificate->sn,'col'=>'6','state'=>'readonly'])
                            @include('template.select-picker',['name'=>'tool_id','label'=>'器具名称','items'=>$tools,'value'=>'id','title'=>'instrument','field1'=>'instrument','field2'=>'model','col'=>'6'])
                            @include('template.input',['name'=>'model','label'=>'规格型号','col'=>'6','state'=>'readonly'])
                            @include('template.select-btn',['name'=>'factory_id','label'=>'生产厂家','class'=>'factory_add','menu'=>'factory','state'=>'disabled','col'=>'6'])
                            @include('template.input',['name'=>'limit','label'=>'测量范围','col'=>'6','state'=>'readonly'])
                            @include('template.select-btn',['name'=>'number_id','label'=>'出厂编号','class'=>'number_add','menu'=>'number','state'=>'disabled','col'=>'6'])
                            @include('template.input',['name'=>'accuracy','label'=>'精确度','col'=>'6','state'=>'readonly'])
                            @include('template.select',['name'=>'department_id','label'=>'检定部门','items'=>$departments,'value'=>'id','field'=>'name','col'=>'6'])
                            @include('template.input',['name'=>'cycle_id','label'=>'检定周期','col'=>'6','state'=>'readonly'])
                            @include('template.select',['name'=>'category_id','label'=>'证书类型','items'=>$categories,'value'=>'id','field'=>'name','col'=>'6'])
                            @include('template.input',['name'=>'abc_id','label'=>'ABC','col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'verification_date','label'=>'检定日期','col'=>'6'])
                            @include('template.input',['name'=>'validity_date','label'=>'有效期','col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'certificate_no','label'=>'统一编号','col'=>'6'])
                            @include('template.input',['name'=>'start','label'=>'启用时间','col'=>'6','state'=>'readonly'])
                            @include('template.select-picker',['name'=>'standard_id[]','label'=>'检定标准','items'=>$standards,'value'=>'id','field1'=>'name2','field2'=>'name1','state'=>'multiple','col'=>'6'])
                            @include('template.input',['name'=>'times','label'=>'检定次数','col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'certificate_name','label'=>'证书名称','col'=>'6'])
                            @include('template.input',['name'=>'plan_id','label'=>'检定计划','col'=>'6','state'=>'readonly'])
                            @include('template.input',['name'=>'remark','label'=>'备注','value'=>$certificate->remark,'col'=>'6'])
                            @include('template.input',['name'=>'file_certificate','label'=>'上传证书','col'=>'6','state'=>'accept="application/pdf"','type'=>'file'])
                        </div>
                        @include('template.btn',['class'=>'submit-edit'])
                    </form>
                </div>
                @if($spares->count()!=0)
                    <div role="tabpanel" class="tab-pane show active" id="spare-tab" aria-labelledby="spare-btn">
                        <div class="col-12">
                            <table id="off-sidebar-table" class="table table-hover table-data table-unselect">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>器具名称</th>
                                    <th>规格型号</th>
                                    <th>出厂编号</th>
                                    <th>检定日期</th>
                                    <th>有效期</th>
                                    <th>检定部门</th>
                                    <th>备注</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($spares as $spare)
                                    <tr @if($spare->validity_date < date("Y-m-d")) class="text-danger" @endif>
                                        <td>{{$spare->order}}</td>
                                        <td>{{$spare->instrument}}</td>
                                        <td>{{$spare->model}}</td>
                                        <td>{{$spare->number}}</td>
                                        <td>{{$spare->verification_date}}</td>
                                        <td>{{$spare->validity_date}}</td>
                                        <td>{{$spare->department}}</td>
                                        <td>{{$spare->remark}}</td>
                                        <td>
                                            <div class="d-flex">
                                                <form action="" onsubmit="return false;">
                                                    {{method_field("put")}}
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="type" value="spare">
                                                    <input type="hidden" name="id" value="{{$certificate->id}}">
                                                    <input type="hidden" name="cause" value="待检">
                                                    <button class="btn btn-outline-secondary btn-sm submit-edit sidebar-url" data-id="{{$spare->id}}">更换</button>
                                                </form>
                                                <form action="" onsubmit="return false;">
                                                    {{method_field("put")}}
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="type" value="spare">
                                                    <input type="hidden" name="id" value="{{$certificate->id}}">
                                                    <input type="hidden" name="cause" value="损坏">
                                                    <button class="btn btn-outline-secondary btn-sm submit-edit sidebar-url mx-1" data-id="{{$spare->id}}">损坏</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @include('template.btn')
                    </div>
                @endif
            @endif
        @endif
    </div>
    <script>
        $('[name="tool_id"],[name="standard_id[]"]').selectpicker();
        $('[name="verification_date"]:not(:read-only)').daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            parentEl: $('.off-sidebar-container'),
            container: '[name="verification_date"]',
        });
    </script>
@endsection
