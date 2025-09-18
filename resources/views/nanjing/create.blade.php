@extends('layout.create')
@section('content_form')
    @include('template.checkbox',['tmp_name'=>'check_auto','tmp_label'=>'自动替换'])
    @foreach($certificates as $certificate)
        <div class="row">
            <input type="hidden" name="group[{{$certificate['json']->id}}][path]" value="{{$certificate['path']}}">
            <input type="hidden" name="group[{{$certificate['json']->id}}][category]" value="{{mb_substr($certificate['category'],0,4)}}">
            <input type="hidden" name="group[{{$certificate['json']->id}}][certificate_no]" value="{{$certificate['json']->zsbh}}">
            <input type="hidden" name="group[{{$certificate['json']->id}}][certificate_name]" value="{{$certificate['json']->name}}">
            @include('template.select-picker',['tmp_name'=>'group['.$certificate['json']->id.'][tool_id]','tmp_label'=>'<a href="/download_nanjing/'.$certificate['json']->zsbh.'?type=show" target="_blank" class="text-info">'.$certificate['json']->name.'</a> '.$certificate['json']->xhgg,'tmp_items'=>$tools,'tmp_value'=>'id','tmp_title'=>'instrument','tmp_field1'=>'instrument','tmp_field2'=>'model','tmp_class'=>$certificate['info']?'':'is-invalid','tmp_selected'=>$certificate['info']?$certificate['tool_id']:'','tmp_validate'=>'id','tmp_col'=>'4'])
            @include('template.select-btn',['tmp_name'=>'group['.$certificate['json']->id.'][factory_id]','tmp_label'=>'<span class="factory_pdf">'.$certificate['factory'].'</span>','tmp_class'=>'factory_add','tmp_menu'=>'factory','tmp_items'=>$certificate['info']?$certificate['factories']:null,'tmp_value'=>'id','tmp_selected'=>$certificate['info']?$certificate['factory_id']:'','tmp_validate'=>'id','tmp_field'=>'name','tmp_select_class'=>$certificate['info']?'':'is-invalid','tmp_show'=>$certificate['info'],'tmp_state'=>$certificate['info']?'':'disabled','tmp_col'=>'4'])
            @include('template.select-btn',['tmp_name'=>'group['.$certificate['json']->id.'][number_id]','tmp_label'=>'<span class="number_pdf">'.($certificate['json']->ccbh=='/'?'':($certificate['json']->ccbh??'')).($certificate['json']->sbbh=='/'?'':($certificate['json']->sbbh??'')).'</span>','tmp_class'=>'number_add','tmp_menu'=>'number','tmp_items'=>$certificate['info']?$certificate['numbers']:null,'tmp_value'=>'id','tmp_selected'=>$certificate['info']?$certificate['number_id']:'','tmp_validate'=>'id','tmp_field'=>'name','tmp_select_class'=>$certificate['info']?'':'is-invalid','tmp_show'=>$certificate['info'],'tmp_state'=>$certificate['info']?'':'disabled','tmp_col'=>'4'])
            @include('template.input',['tmp_name'=>'group['.$certificate['json']->id.'][verification_date]','tmp_label'=>'检定日期','tmp_value'=>$certificate['json']->jdrq,'tmp_col'=>'4'])
            @include('template.select-picker',['tmp_name'=>'group['.$certificate['json']->id.'][standard_id][]','tmp_label'=>'检定标准','tmp_selected'=>collect($certificate['standard']),'tmp_items'=>$standards,'tmp_value'=>'id','tmp_validate'=>'id','tmp_field1'=>'name2','tmp_field2'=>'name1','tmp_state'=>'multiple','tmp_col'=>'4'])
            @include('template.input-btn',['tmp_name'=>'group['.$certificate['json']->id.'][remark]','tmp_label'=>'备注','tmp_class'=>'btn-outline-danger group-delete','tmp_col'=>'4'])
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
