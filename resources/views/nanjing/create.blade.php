@extends('layout.create')
@section('content_form')
    @include('template.checkbox',['tmp_name'=>'check_auto','tmp_label'=>'自动替换'])
    @foreach($certificates as $certificate)
        <div class="row">
            <input type="hidden" name="group[{{$certificate['id']}}][path]" value="{{$certificate['path']}}">
            <input type="hidden" name="group[{{$certificate['id']}}][category]" value="{{$certificate['category']}}">
            <input type="hidden" name="group[{{$certificate['id']}}][certificate_no]" value="{{$certificate['zsbh']}}">
            <input type="hidden" name="group[{{$certificate['id']}}][certificate_name]" value="{{$certificate['name']}}">
            @include('template.select-picker',['tmp_name'=>"group[$certificate[id]][tool_id]",'tmp_label'=>"<a href='/download_nanjing/$certificate[zsbh]?type=show' target='_blank' class='text-info'>$certificate[name]</a> $certificate[xhgg]",'tmp_items'=>$tools,'tmp_selected'=>$certificate['tool_id'],'tmp_validate'=>'id','tmp_value'=>'id','tmp_title'=>'instrument','tmp_field1'=>'instrument','tmp_field2'=>'model','tmp_col'=>'4'])
            @include('template.select-btn',['tmp_menu'=>'factory','tmp_id'=>"$certificate[tool_id]&name=$certificate[factory]",'tmp_name'=>"group[$certificate[id]][factory_id]",'tmp_class'=>'factory_add','tmp_label'=>"<span class='factory_pdf'>$certificate[factory]</span>",'tmp_items'=>$certificate['factories'],'tmp_selected'=>$certificate['factory_id'],'tmp_validate'=>'id','tmp_value'=>'id','tmp_field'=>'name','tmp_reload'=>"group[$certificate[id]][tool_id]",'tmp_col'=>'4'])
            @include('template.select-btn',['tmp_menu'=>'number','tmp_id'=>"$certificate[factory_id]&name=$certificate[ccbh]",'tmp_name'=>"group[$certificate[id]][number_id]",'tmp_class'=>'number_add','tmp_label'=>"<span class='number_pdf'>$certificate[ccbh]</span>",'tmp_items'=>$certificate['numbers'],'tmp_selected'=>$certificate['number_id'],'tmp_validate'=>'id','tmp_value'=>'id','tmp_field'=>'name','tmp_reload'=>"group[$certificate[id]][factory_id]",'tmp_col'=>'4'])
            @include('template.input',['tmp_name'=>"group[$certificate[id]][verification_date]",'tmp_label'=>'检定日期','tmp_value'=>$certificate['jdrq'],'tmp_col'=>'4'])
            @include('template.select-picker',['tmp_name'=>"group[$certificate[id]][standard_id][]",'tmp_label'=>'检定标准','tmp_items'=>$standards,'tmp_selected'=>collect($certificate['standard']),'tmp_validate'=>'id','tmp_value'=>'id','tmp_field1'=>'name2','tmp_field2'=>'name1','tmp_state'=>'multiple','tmp_col'=>'4'])
            @include('template.input-btn',['tmp_name'=>"group[$certificate[id]][remark]",'tmp_class'=>'btn-outline-danger group-delete','tmp_label'=>'备注','tmp_col'=>'4'])
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
