@extends('layout.show')
@section('content_title')
    @if($certificate->valid)
        @if($certificate->position1=='备用')
            @include('template.nav-tab',['tmp_name'=>'apply','tmp_label'=>'使用','tmp_active'=>true])
        @else
            @if($spares->count()!=0)
                @include('template.nav-tab',['tmp_name'=>'spare','tmp_label'=>'备用','tmp_active'=>true])
            @endif
            @include('template.nav-tab',['tmp_name'=>'replace','tmp_label'=>'更新','tmp_active'=>$spares->count()==0])
        @endif
    @endif
    @include('template.nav-tab',['tmp_name'=>'edit','tmp_label'=>'修改','tmp_active'=>$certificate->valid==0])
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
                    @include('template.input',['tmp_name'=>'position_id','tmp_label'=>'岗位','tmp_value'=>$certificate->position1,'tmp_col'=>'6','tmp_state'=>'disabled'])
                    @include('template.input',['tmp_name'=>'sn','tmp_label'=>'序号','tmp_value'=>$certificate->sn,'tmp_col'=>'6','tmp_state'=>'readonly'])
                    @include('template.input',['tmp_name'=>'tool_id','tmp_label'=>'器具名称','tmp_value'=>$certificate->instrument,'tmp_col'=>'6','tmp_state'=>'disabled'])
                    @include('template.input',['tmp_name'=>'model','tmp_label'=>'规格型号','tmp_value'=>$certificate->model,'tmp_col'=>'6','tmp_state'=>'readonly'])
                    @include('template.input',['tmp_name'=>'factory_id','tmp_label'=>'生产厂家','tmp_value'=>$certificate->factory,'tmp_col'=>'6','tmp_state'=>'disabled'])
                    @include('template.input',['tmp_name'=>'limit','tmp_label'=>'测量范围','tmp_value'=>$certificate->limit,'tmp_col'=>'6','tmp_state'=>'readonly'])
                    @include('template.input',['tmp_name'=>'number_id','tmp_label'=>'出厂编号','tmp_value'=>$certificate->number,'tmp_col'=>'6','tmp_state'=>'disabled'])
                    @include('template.input',['tmp_name'=>'accuracy','tmp_label'=>'精确度','tmp_value'=>$certificate->accuracy,'tmp_col'=>'6','tmp_state'=>'readonly'])
                    @include('template.select',['tmp_name'=>'department_id','tmp_label'=>'检定部门','tmp_items'=>$departments,'tmp_value'=>'id','tmp_field'=>'name','tmp_col'=>'6','tmp_selected'=>$certificate->department_id,'tmp_validate'=>'id'])
                    @include('template.input',['tmp_name'=>'cycle_id','tmp_label'=>'检定周期','tmp_value'=>$certificate->cycle,'tmp_col'=>'6','tmp_state'=>'readonly'])
                    @include('template.select',['tmp_name'=>'category_id','tmp_label'=>'证书类型','tmp_items'=>$categories,'tmp_value'=>'id','tmp_field'=>'name','tmp_col'=>'6','tmp_selected'=>$certificate->category_id,'tmp_validate'=>'id'])
                    @include('template.input',['tmp_name'=>'abc_id','tmp_label'=>'ABC','tmp_value'=>$certificate->abc,'tmp_col'=>'6','tmp_state'=>'readonly'])
                    @include('template.input',['tmp_name'=>'verification_date','tmp_label'=>'检定日期','tmp_value'=>$certificate->verification_date,'tmp_col'=>'6'])
                    @include('template.input',['tmp_name'=>'validity_date','tmp_label'=>'有效期','tmp_value'=>$certificate->validity_date,'tmp_col'=>'6','tmp_state'=>'readonly'])
                    @include('template.input',['tmp_name'=>'certificate_no','tmp_label'=>'统一编号','tmp_value'=>$certificate->certificate_no,'tmp_col'=>'6'])
                    @include('template.input',['tmp_name'=>'start','tmp_label'=>'启用时间','tmp_value'=>$certificate->start,'tmp_col'=>'6','tmp_state'=>'readonly'])
                    @include('template.select-picker',['tmp_name'=>'standard_id[]','tmp_label'=>'检定标准','tmp_selected'=>$certificate->standards,'tmp_items'=>$standards,'tmp_value'=>'id','tmp_validate'=>'id','tmp_field1'=>'name2','tmp_field2'=>'name1','tmp_state'=>'multiple','tmp_col'=>'6'])
                    @include('template.input',['tmp_name'=>'times','tmp_label'=>'检定次数','tmp_value'=>$certificate->times,'tmp_col'=>'6','tmp_state'=>'readonly'])
                    @include('template.input',['tmp_name'=>'certificate_name','tmp_label'=>'证书名称','tmp_value'=>$certificate->certificate_name,'tmp_col'=>'6'])
                    @include('template.input',['tmp_name'=>'plan','tmp_label'=>'检定计划','tmp_value'=>$certificate->plan,'tmp_col'=>'6','tmp_state'=>'readonly'])
                    @include('template.input',['tmp_name'=>'remark','tmp_label'=>'备注','tmp_value'=>$certificate->remark,'tmp_col'=>'6'])
                    @include('template.input',['tmp_name'=>'file_certificate','tmp_label'=>'上传证书','tmp_col'=>'6','tmp_state'=>'accept="application/pdf"','tmp_type'=>'file'])
                </div>
                @include('template.sidebar-btn',['tmp_class'=>'submit-edit'])
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
                            @include('template.select-group',['tmp_name'=>'position_id','tmp_label'=>'岗位','tmp_items'=>$positions,'tmp_value'=>'id','tmp_field1'=>'name','tmp_field2'=>'code','tmp_col'=>'6'])
                            @include('template.input',['tmp_name'=>'sn','tmp_label'=>'序号','tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'tool_id','tmp_label'=>'器具名称','tmp_value'=>$certificate->instrument,'tmp_col'=>'6','tmp_state'=>'disabled'])
                            @include('template.input',['tmp_name'=>'model','tmp_label'=>'规格型号','tmp_value'=>$certificate->model,'tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'factory_id','tmp_label'=>'生产厂家','tmp_value'=>$certificate->factory,'tmp_col'=>'6','tmp_state'=>'disabled'])
                            @include('template.input',['tmp_name'=>'limit','tmp_label'=>'测量范围','tmp_value'=>$certificate->limit,'tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'number_id','tmp_label'=>'出厂编号','tmp_value'=>$certificate->number,'tmp_col'=>'6','tmp_state'=>'disabled'])
                            @include('template.input',['tmp_name'=>'accuracy','tmp_label'=>'精确度','tmp_value'=>$certificate->accuracy,'tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'department_id','tmp_label'=>'检定部门','tmp_value'=>$certificate->department,'tmp_col'=>'6','tmp_state'=>'disabled'])
                            @include('template.input',['tmp_name'=>'cycle_id','tmp_label'=>'检定周期','tmp_value'=>$certificate->cycle,'tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'category_id','tmp_label'=>'证书类型','tmp_value'=>$certificate->category,'tmp_col'=>'6','tmp_state'=>'disabled'])
                            @include('template.input',['tmp_name'=>'abc_id','tmp_label'=>'ABC','tmp_value'=>$certificate->abc,'tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'verification_date','tmp_label'=>'检定日期','tmp_value'=>$certificate->verification_date,'tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'validity_date','tmp_label'=>'有效期','tmp_value'=>$certificate->validity_date,'tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'certificate_no','tmp_label'=>'统一编号','tmp_value'=>$certificate->certificate_no,'tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'start','tmp_label'=>'启用时间','tmp_value'=>$certificate->start,'tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'standard_id','tmp_label'=>'检定标准','tmp_value'=>$certificate->standard,'tmp_col'=>'6','tmp_state'=>'disabled'])
                            @include('template.input',['tmp_name'=>'times','tmp_label'=>'检定次数','tmp_value'=>$certificate->times,'tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'certificate_name','tmp_label'=>'证书名称','tmp_value'=>$certificate->certificate_name,'tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'plan','tmp_label'=>'检定计划','tmp_value'=>$certificate->plan,'tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'remark','tmp_label'=>'备注','tmp_value'=>$certificate->remark,'tmp_col'=>'6'])
                            @include('template.input',['tmp_name'=>'file_certificate','tmp_label'=>'上传证书','tmp_col'=>'6','tmp_state'=>'accept="application/pdf"','tmp_type'=>'file'])
                        </div>
                        @include('template.sidebar-btn',['tmp_class'=>'submit-edit'])
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
                            <input type="hidden" name="position_id" value="{{$certificate->position_id1}}">
                            <input type="hidden" name="number" value="{{$certificate->number_id}}">
                            @include('template.select',['tmp_name'=>'cause','tmp_label'=>'损坏','tmp_selected'=>'0','tmp_col'=>'3'])
                            @include('template.input',['tmp_name'=>'position_id','tmp_label'=>'岗位','tmp_value'=>$certificate->position1,'tmp_col'=>'3','tmp_state'=>'disabled'])
                            @include('template.input',['tmp_name'=>'sn','tmp_label'=>'序号','tmp_value'=>$certificate->sn,'tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.select-picker',['tmp_name'=>'tool_id','tmp_label'=>'器具名称','tmp_items'=>$tools,'tmp_value'=>'id','tmp_title'=>'instrument','tmp_field1'=>'instrument','tmp_field2'=>'model','tmp_col'=>'6'])
                            @include('template.input',['tmp_name'=>'model','tmp_label'=>'规格型号','tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.select-btn',['tmp_name'=>'factory_id','tmp_label'=>'生产厂家','tmp_class'=>'factory_add','tmp_menu'=>'factory','tmp_state'=>'disabled','tmp_col'=>'6'])
                            @include('template.input',['tmp_name'=>'limit','tmp_label'=>'测量范围','tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.select-btn',['tmp_name'=>'number_id','tmp_label'=>'出厂编号','tmp_class'=>'number_add','tmp_menu'=>'number','tmp_state'=>'disabled','tmp_col'=>'6'])
                            @include('template.input',['tmp_name'=>'accuracy','tmp_label'=>'精确度','tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.select',['tmp_name'=>'department_id','tmp_label'=>'检定部门','tmp_items'=>$departments,'tmp_value'=>'id','tmp_field'=>'name','tmp_col'=>'6'])
                            @include('template.input',['tmp_name'=>'cycle_id','tmp_label'=>'检定周期','tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.select',['tmp_name'=>'category_id','tmp_label'=>'证书类型','tmp_items'=>$categories,'tmp_value'=>'id','tmp_field'=>'name','tmp_col'=>'6'])
                            @include('template.input',['tmp_name'=>'abc_id','tmp_label'=>'ABC','tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'verification_date','tmp_label'=>'检定日期','tmp_col'=>'6'])
                            @include('template.input',['tmp_name'=>'validity_date','tmp_label'=>'有效期','tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'certificate_no','tmp_label'=>'统一编号','tmp_col'=>'6'])
                            @include('template.input',['tmp_name'=>'start','tmp_label'=>'启用时间','tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.select-picker',['tmp_name'=>'standard_id[]','tmp_label'=>'检定标准','tmp_items'=>$standards,'tmp_value'=>'id','tmp_field1'=>'name2','tmp_field2'=>'name1','tmp_state'=>'multiple','tmp_col'=>'6'])
                            @include('template.input',['tmp_name'=>'times','tmp_label'=>'检定次数','tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'certificate_name','tmp_label'=>'证书名称','tmp_col'=>'6'])
                            @include('template.input',['tmp_name'=>'plan_id','tmp_label'=>'检定计划','tmp_col'=>'6','tmp_state'=>'readonly'])
                            @include('template.input',['tmp_name'=>'remark','tmp_label'=>'备注','tmp_value'=>$certificate->remark,'tmp_col'=>'6'])
                            @include('template.input',['tmp_name'=>'file_certificate','tmp_label'=>'上传证书','tmp_col'=>'6','tmp_state'=>'accept="application/pdf"','tmp_type'=>'file'])
                        </div>
                        @include('template.sidebar-btn',['tmp_class'=>'submit-edit'])
                    </form>
                </div>
                @if($spares->count()!=0)
                    <div role="tabpanel" class="tab-pane show active" id="spare-tab" aria-labelledby="spare-btn">
                        <ul class="nav">
                            <form action="" onsubmit="return false;">
                                {{method_field("put")}}
                                {{csrf_field()}}
                                <input type="hidden" name="type" value="spare">
                                <input type="hidden" name="id" value="{{$certificate->id}}">
                                <input type="hidden" name="cause" value="待检">
                                @include('template.nav-btn',['tmp_label'=>'更换','tmp_menu'=>'','tmp_url'=>'certificate','tmp_class'=>'submit-edit check-single'])
                            </form>
                            <form action="" onsubmit="return false;">
                                {{method_field("put")}}
                                {{csrf_field()}}
                                <input type="hidden" name="type" value="spare">
                                <input type="hidden" name="id" value="{{$certificate->id}}">
                                <input type="hidden" name="cause" value="损坏">
                                @include('template.nav-btn',['tmp_label'=>'损坏','tmp_menu'=>'','tmp_url'=>'certificate','tmp_class'=>'submit-edit check-single'])
                            </form>
                        </ul>
                        @include('template.table',['tmp_menu'=>"certificate",'tmp_fields'=>['','序号','器具名称','规格型号','出厂编号','检定日期','有效期','检定部门','备注'],'tmp_table_id'=>'off-sidebar','tmp_class'=>'table-data','tmp_items'=>$spares])
                        @include('template.sidebar-btn')
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
