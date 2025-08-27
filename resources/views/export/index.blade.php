@extends("layout.index")
@section('content_btn')
    <li class="nav-item">
        <a class="nav-link btn-add" data-menu="export" href="#" data-pos="left" data-id="1">设置</a>
    </li>
@endsection
@section('content_table')
    <form action="/export/1" method="post" enctype="multipart/form-data">
        {{method_field("put")}}
        {{csrf_field()}}
        <div class="row m-0">
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                <div>文件类型</div>
                <select name="contents[]" class="selectpicker w-100" multiple title="请选择...">
                    <option selected>台账</option>
                    <option>核对台账</option>
                    <option>标准台账</option>
                    <option>计量证书</option>
                    <option>监理资料</option>
                </select>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                <div>计量类别</div>
                <select name="type[]" class="selectpicker w-100" multiple title="请选择...">
                    @foreach($positions->where('level',2) as $position)
                        <option selected>{{$position->name}}</option>
                    @endforeach
                </select>
                <div>
                    <input type="checkbox" name="check_type" id="check_type">
                    <label for="check_type">导出多个文件</label>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                <div>单位</div>
                <select name="position[]" class="selectpicker w-100" multiple title="请选择...">
                    @foreach($positions->where('level',1) as $position)
                        <option selected>{{$position->name}}</option>
                    @endforeach
                </select>
                <div>
                    <input type="checkbox" name="check_position" id="check_position">
                    <label for="check_position">导出多个文件</label>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                <div>选择日期</div>
                <input type="text" class="form-control w-100" name="daterange" id="daterange" disabled>
                <input type="checkbox" name="check_daterange" id="check_daterange">
                <label for="check_daterange">选择日期范围</label>
            </div>
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
