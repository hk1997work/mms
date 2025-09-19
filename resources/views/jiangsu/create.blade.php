@extends('layout.create')
@section('content_form')
    @include('template.checkbox',['tmp_name'=>'check_auto','tmp_label'=>'自动替换'])
    @foreach($certificates as $certificate)
        <div class="row">
            <input type="hidden" name="group[{{$certificate['json']->zsId}}][path]" value="{{$certificate['path']}}">
            <input type="hidden" name="group[{{$certificate['json']->zsId}}][category]" value="{{mb_substr($certificate['category'],0,4)}}">
            <input type="hidden" name="group[{{$certificate['json']->zsId}}][certificate_no]" value="{{$certificate['json']->zsZsh}}">
            <input type="hidden" name="group[{{$certificate['json']->zsId}}][certificate_name]" value="{{$certificate['json']->zsQjmc}}">
            @include('template.select-picker',['tmp_name'=>'group['.$certificate['json']->zsId.'][tool_id]','tmp_label'=>'<a href="/download_jiangsu/'.$certificate['json']->zsZsh.'?type=show" target="_blank" class="text-info">'.$certificate['json']->zsQjmc.'</a> '.$certificate['json']->zsXhgg,'tmp_items'=>$tools,'tmp_value'=>'id','tmp_title'=>'instrument','tmp_field1'=>'instrument','tmp_field2'=>'model','tmp_class'=>$certificate['info']?'':'is-invalid','tmp_selected'=>$certificate['info']?$certificate['tool_id']:'','tmp_validate'=>'id','tmp_col'=>'4'])
            @include('template.select-btn',['tmp_name'=>'group['.$certificate['json']->zsId.'][factory_id]','tmp_label'=>'<span class="factory_pdf">'.$certificate['json']->zsZzc.'</span>','tmp_class'=>'factory_add','tmp_menu'=>'factory','tmp_items'=>$certificate['info']?$certificate['factories']:null,'tmp_value'=>'id','tmp_selected'=>$certificate['info']?$certificate['factory_id']:'','tmp_validate'=>'id','tmp_field'=>'name','tmp_select_class'=>$certificate['info']?'':'is-invalid','tmp_show'=>$certificate['info'],'tmp_state'=>$certificate['info']?'':'disabled','tmp_id'=>$certificate['info']?$certificate['tool_id'].'&name='.$certificate['json']->zsZzc:'','tmp_reload'=>'group['.$certificate['json']->zsId.'][tool_id]','tmp_col'=>'4'])
            @include('template.select-btn',['tmp_name'=>'group['.$certificate['json']->zsId.'][number_id]','tmp_label'=>'<span class="number_pdf">'.($certificate['json']->zsCcbh=='/'?'':($certificate['json']->zsCcbh??'')).($certificate['json']->zsSbbh=='/'?'':($certificate['json']->zsSbbh??'')).'</span>','tmp_class'=>'number_add','tmp_menu'=>'number','tmp_items'=>$certificate['info']?$certificate['numbers']:null,'tmp_value'=>'id','tmp_selected'=>$certificate['info']?$certificate['number_id']:'','tmp_validate'=>'id','tmp_field'=>'name','tmp_select_class'=>$certificate['info']?'':'is-invalid','tmp_show'=>$certificate['info'],'tmp_state'=>$certificate['info']?'':'disabled','tmp_id'=>$certificate['info']?$certificate['factory_id'].'&name='.($certificate['json']->zsCcbh=='/'?'':($certificate['json']->zsCcbh??'')).($certificate['json']->zsSbbh=='/'?'':($certificate['json']->zsSbbh??'')):'','tmp_reload'=>'group['.$certificate['json']->zsId.'][factory_id]','tmp_col'=>'4'])
            @include('template.input',['tmp_name'=>'group['.$certificate['json']->zsId.'][verification_date]','tmp_label'=>'检定日期','tmp_value'=>$certificate['json']->zsJdrq,'tmp_col'=>'4'])
            @include('template.select-picker',['tmp_name'=>'group['.$certificate['json']->zsId.'][standard_id][]','tmp_label'=>'检定标准','tmp_selected'=>collect($certificate['standard']),'tmp_items'=>$standards,'tmp_value'=>'id','tmp_validate'=>'id','tmp_field1'=>'name2','tmp_field2'=>'name1','tmp_state'=>'multiple','tmp_col'=>'4'])
            @include('template.input-btn',['tmp_name'=>'group['.$certificate['json']->zsId.'][remark]','tmp_label'=>'备注','tmp_class'=>'btn-outline-danger group-delete','tmp_col'=>'4'])
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