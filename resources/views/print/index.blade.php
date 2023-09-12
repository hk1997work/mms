@extends('layout.index')
@section('content_btn')
    <li class="nav-item"><a onclick="submit()" class="nav-link">提 交</a></li>
@endsection
@section('content_table')
    <form action="/print" method="post" enctype="multipart/form-data" id="form_print">
        {{csrf_field()}}
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 mb-3">
            <label class="form-control-label">起始行(1-10)</label>
            <input type="text" name="row" id="row" value="1" class="form-control" required>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 mb-3">
            <label class="form-control-label">起始列(1-5)</label>
            <input type="text" name="column" id="column" value="1" class="form-control" required>
            @foreach($errors->all() as $error)
                <div class="text-danger">{{$error}}</div>
            @endforeach
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 mb-3">
            <div class="styled-checkbox mt-3">
                <input type="checkbox" name="check_position" id="check_position">
                <label for="check_position">打印岗位信息</label>
            </div>
        </div>
        <table id="export-table" class="table table-hover mb-0">
            <thead>
            <tr>
                <th class="d-none d-xl-table-cell"></th>
                <th class="d-none d-xl-table-cell">序号</th>
                <th class="d-none d-xl-table-cell">岗位</th>
                <th class="d-none d-xl-table-cell">器具名称</th>
                <th class="d-none d-xl-table-cell">规格型号</th>
                <th class="d-none d-xl-table-cell">出厂编号</th>
                <th class="d-none d-xl-table-cell">检定日期</th>
                <th class="d-none d-xl-table-cell">有效期</th>
                <th class="d-none d-xl-table-cell">检定部门</th>
                <th>操作时间</th>
                <th class="d-xl-none">标签</th>
            </tr>
            </thead>
            <tbody>
            @foreach($certificates as $certificate)
                <tr>
                    <td>
                        <div class="d-xl-none">{{$certificate->updated_at}}</div>
                        <div class="styled-checkbox mt-3">
                            <input type="checkbox" name="cb[{{$certificate->id}}]" id="cb[{{$certificate->id}}]">
                            <label for="cb[{{$certificate->id}}]"></label>
                        </div>
                    </td>
                    <td class="d-none d-xl-table-cell">{{$certificate->order}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->position}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->instrument}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->model}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->number}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->verification_date}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->validity_date}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->department}}</td>
                    <td class="d-none d-xl-table-cell">{{$certificate->updated_at}}</td>
                    <td class="d-xl-none">{{$certificate->instrument}}<br>{{$certificate->model}}<br>{{$certificate->number}}<br>{{$certificate->verification_date}}<br>{{$certificate->validity_date}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </form>
    <script>
        function submit() {
            if ($("#export-table input[type='checkbox']:checked").length > 0) {
                $("#form_print").submit()
            } else {
                notifications('请选择数据!')
            }
        }
    </script>
@endsection
