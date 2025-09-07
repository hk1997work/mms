@extends('layout.create')
@section('content_form')
    <div class="row">
        @include('template.select-group',['tmp_name'=>'position_id','tmp_label'=>'岗位','tmp_items'=>$positions,'tmp_value'=>'id','tmp_field1'=>'name','tmp_field2'=>'code','tmp_col'=>'6'])
        @include('template.input',['tmp_name'=>'sn','tmp_label'=>'序号','tmp_col'=>'6','tmp_state'=>'readonly'])
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
        @include('template.input',['tmp_name'=>'remark','tmp_label'=>'备注','tmp_col'=>'6'])
        @include('template.input',['tmp_name'=>'file_certificate','tmp_label'=>'上传证书','tmp_col'=>'6','tmp_state'=>'accept="application/pdf"','tmp_type'=>'file'])
    </div>
    <script>
        $('[name="tool_id"],[name="standard_id[]"]').selectpicker();
        $('[name="verification_date"]').daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            parentEl: $('.off-sidebar-container'),
            container: '[name="verification_date"]',
        });
    </script>
@endsection
