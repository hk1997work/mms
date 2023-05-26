@extends('layout.index')
@section('content_btn')
    <li class="nav-item"><a onclick="modal()" class="nav-link">提 交</a></li>
@endsection
@section('content_table')
    <form action="" onsubmit="return false;" id="form_setting">
        {{csrf_field()}}
        <table id="export-table" class="table table-hover mb-0">
            <thead>
            <tr>
                <th class="d-none d-xl-table-cell"></th>
                <th>序号</th>
                <th class="d-none d-xl-table-cell">岗位</th>
                <th class="d-none d-xl-table-cell">器具名称</th>
                <th class="d-none d-xl-table-cell">规格型号</th>
                <th class="d-none d-xl-table-cell">出厂编号</th>
                <th class="d-none d-xl-table-cell">检定日期</th>
                <th class="d-none d-xl-table-cell">有效期</th>
                <th class="d-none d-xl-table-cell">检定部门</th>
                <th class="d-none d-xl-table-cell">备注</th>
                <th class="d-xl-none">标签</th>
            </tr>
            </thead>
            <tbody>
            @foreach($certificates as $certificate)
                <tr>
                    <td>
                        <div class="d-xl-none">{{$certificate->order}}<br>{{$certificate->position}}<br>{{$certificate->instrument}}<br>{{$certificate->model}}</div>
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
                    <td class="d-none d-xl-table-cell">{{$certificate->remark}}</td>
                    <td class="d-xl-none">{{$certificate->number}}<br>{{$certificate->verification_date}}<br>{{$certificate->validity_date}}<br>{{$certificate->department}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </form>
    <script>
        function modal() {
            $.ajax({
                url: "/supervision",
                type: "POST",
                data: new FormData($("#form_setting")[0]),
                processData: false,  // 不处理数据
                contentType: false,   // 不设置内容类型
                success: function (result) {
                    if (result == false) {
                        notifications('提交失败');
                    } else {
                        $(".modal-body").remove()
                        $('.modal-title').text('检查情况')
                        var str = '<div class="modal-body"><div>' + result + '</div></div>';
                        $(".modal-header").after(str);
                        $('#delete').modal('show')
                    }
                },
                error: function (xhr) {
                    if (xhr.status == 401) {
                        document.location.reload();
                    } else {
                        notifications('提交失败');
                    }
                }
            });
        }
    </script>
@endsection
