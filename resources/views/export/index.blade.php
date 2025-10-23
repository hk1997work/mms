@extends("layout.index")
@section('content_btn')
    @include('template.nav-btn',['tmp_menu'=>'export','tmp_id'=>'true','tmp_class'=>'btn-add','tmp_label'=>'设置','tmp_pos'=>'left'])
@endsection
@section('content_table')
    <form action="/export/1" method="post" enctype="multipart/form-data">
        {{method_field("put")}}
        {{csrf_field()}}
        <div class="row m-0">
            @include('template.controller-checkbox',['tmp_name'=>'contents[]','tmp_label'=>'文件类型','tmp_items'=>['台账','核对台账','标准台账','计量证书','监理资料'],'tmp_selected'=>''])
            @include('template.controller-checkbox',['tmp_name'=>'category[]','tmp_label'=>'证书类型','tmp_items'=>$categories->pluck('name'),'tmp_selected'=>true,'tmp_check'=>'导出多个文件'])
            @include('template.controller-checkbox',['tmp_name'=>'daterange','tmp_label'=>'选择日期','tmp_input'=>true,'tmp_check'=>'选择日期范围'])
            @include('template.controller-checkbox',['tmp_name'=>'type[]','tmp_label'=>'计量类别','tmp_items'=>$positions->where('level',2)->pluck('name'),'tmp_selected'=>true,'tmp_check'=>'导出多个文件'])
            @include('template.controller-checkbox',['tmp_name'=>'position[]','tmp_label'=>'单位','tmp_items'=>$positions->where('level',1)->pluck('name'),'tmp_selected'=>true,'tmp_check'=>'导出多个文件'])
            @include('template.controller-checkbox',['tmp_name'=>'class[]','tmp_label'=>'班组','tmp_items'=>$positions->where('level',3)->pluck('name'),'tmp_selected'=>true,'tmp_check'=>'导出多个文件'])
            <div class="d-flex align-items-center justify-content-end">
                <button class="btn btn-outline-primary" type="submit">下 载</button>
            </div>
        </div>
    </form>
@endsection
@push('page-js-after')
    <script>
        $('#check_daterange').change(function () {
            $('#daterange').prop('disabled', !this.checked);
        });
    </script>
@endpush
