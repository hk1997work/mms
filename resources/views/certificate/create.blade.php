@extends('layout.create')
@section('content_form')
    <div class="row">
        @include('template.select-group',['name'=>'position_id','label'=>'岗位','items'=>$positions,'value'=>'id','field1'=>'name','field2'=>'code','col'=>'6'])
        @include('template.input',['name'=>'sn','label'=>'序号','col'=>'6','state'=>'readonly'])
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
        @include('template.input',['name'=>'remark','label'=>'备注','col'=>'6'])
        @include('template.input',['name'=>'file_certificate','label'=>'上传证书','col'=>'6','state'=>'accept="application/pdf"','type'=>'file'])
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
