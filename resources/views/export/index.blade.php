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
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="mt-3 mb-2">文件类型</div>
                <select name="contents[]" class="selectpicker w-100" multiple title="请选择..." required>
                    <option selected>台账</option>
                    <option>核对台账</option>
                    <option>标准台账</option>
                    <option>计量证书</option>
                    <option>监理资料</option>
                </select>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="mt-3 mb-2">证书类型</div>
                <select name="category[]" class="selectpicker w-100" multiple title="请选择..." required>
                    @foreach($categories as $category)
                        <option selected>{{$category->name}}</option>
                    @endforeach
                </select>
                <div class="mt-2">
                    <input type="checkbox" name="check_category" id="check_category">
                    <label for="check_category">导出多个文件</label>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="mt-3 mb-2">选择日期</div>
                <input type="text" class="form-control w-100" name="daterange" id="daterange" disabled>
                <div class="mt-2">
                    <input type="checkbox" name="check_daterange" id="check_daterange">
                    <label for="check_daterange">选择日期范围</label>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="mt-3 mb-2">计量类别</div>
                <select name="type[]" class="selectpicker w-100" multiple title="请选择..." required>
                    @foreach($positions->where('level',2) as $position)
                        <option selected>{{$position->name}}</option>
                    @endforeach
                </select>
                <div class="mt-2">
                    <input type="checkbox" name="check_type" id="check_type">
                    <label for="check_type">导出多个文件</label>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="mt-3 mb-2">单位</div>
                <select name="position[]" class="selectpicker w-100" multiple title="请选择..." required>
                    @foreach($positions->where('level',1) as $position)
                        <option selected>{{$position->name}}</option>
                    @endforeach
                </select>
                <div class="mt-2">
                    <input type="checkbox" name="check_position" id="check_position">
                    <label for="check_position">导出多个文件</label>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="mt-3 mb-2">班组</div>
                <select name="class[]" class="selectpicker w-100" multiple title="请选择..." required>
                    @foreach($positions->where('level',3) as $position)
                        <option selected>{{$position->name}}</option>
                    @endforeach
                </select>
                <div class="mt-2">
                    <input type="checkbox" name="check_class" id="check_class">
                    <label for="check_class">导出多个文件</label>
                </div>
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
