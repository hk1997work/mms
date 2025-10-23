@extends('layout.create')
@section('content_form')
    @include('template.checkbox',['tmp_name'=>'check_auto','tmp_label'=>'自动替换'])
    @foreach($certificates as $certificate)
        <div class="row">
            <input type="hidden" name="group[{{$certificate['zsId']}}][path]" value="{{$certificate['path']}}">
            <input type="hidden" name="group[{{$certificate['zsId']}}][category]" value="{{$certificate['category']}}">
            <input type="hidden" name="group[{{$certificate['zsId']}}][certificate_no]" value="{{$certificate['zsZsh']}}">
            <input type="hidden" name="group[{{$certificate['zsId']}}][certificate_name]" value="{{$certificate['zsQjmc']}}">
            @include('template.select-picker',['tmp_name'=>"group[$certificate[zsId]][tool_id]",'tmp_label'=>"<a href='/download_jiangsu/$certificate[zsZsh]?type=show' target='_blank' class='text-info'>$certificate[zsQjmc]</a> $certificate[zsXhgg]",'tmp_items'=>$tools,'tmp_selected'=>$certificate['tool_id'],'tmp_validate'=>'id','tmp_value'=>'id','tmp_title'=>'instrument','tmp_field1'=>'instrument','tmp_field2'=>'model','tmp_col'=>'4'])
            @include('template.select-btn',['tmp_menu'=>'factory','tmp_id'=>"$certificate[tool_id]&name=$certificate[zsZzc]",'tmp_name'=>"group[$certificate[zsId]][factory_id]",'tmp_class'=>'factory_add','tmp_label'=>"<span class='factory_pdf'>$certificate[zsZzc]</span>",'tmp_items'=>$certificate['factories'],'tmp_selected'=>$certificate['factory_id'],'tmp_validate'=>'id','tmp_value'=>'id','tmp_field'=>'name','tmp_reload'=>"group[$certificate[zsId]][tool_id]",'tmp_col'=>'4'])
            @include('template.select-btn',['tmp_menu'=>'number','tmp_id'=>"$certificate[factory_id]&name=$certificate[zsCcbh]",'tmp_name'=>"group[$certificate[zsId]][number_id]",'tmp_class'=>'number_add','tmp_label'=>"<span class='number_pdf'>$certificate[zsCcbh]</span>",'tmp_items'=>$certificate['numbers'],'tmp_selected'=>$certificate['number_id'],'tmp_validate'=>'id','tmp_value'=>'id','tmp_field'=>'name','tmp_reload'=>"group[$certificate[zsId]][factory_id]",'tmp_col'=>'4'])
            @include('template.input',['tmp_name'=>"group[$certificate[zsId]][verification_date]",'tmp_label'=>'检定日期','tmp_value'=>$certificate['zsJdrq'],'tmp_col'=>'4'])
            @include('template.select-picker',['tmp_name'=>"group[$certificate[zsId]][standard_id][]",'tmp_label'=>'检定标准','tmp_items'=>$standards,'tmp_selected'=>collect($certificate['standard']),'tmp_validate'=>'id','tmp_value'=>'id','tmp_field1'=>'name2','tmp_field2'=>'name1','tmp_state'=>'multiple','tmp_col'=>'4'])
            @include('template.input-btn',['tmp_name'=>"group[$certificate[zsId]][remark]",'tmp_class'=>'btn-outline-danger group-delete','tmp_label'=>'备注','tmp_col'=>'4'])
        </div>
    @endforeach
    <script>
        $('[name^="group["][name$="][tool_id]"],[name^="group["][name$="][standard_id][]"]').selectpicker();
        $('[name^="group["][name$="][verification_date]"]').daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            parentEl: $('.off-sidebar-container'),
            container: '[name^="group["][name$="][verification_date]"]',
        });
    </script>
@endsection