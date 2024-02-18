@extends('layout.create')
@section('content_form')
    <div class="col-12">
        <table id="no-ajax-table" class="table table-hover mb-0">
            <thead>
            <tr>
                <th></th>
                <th>序号</th>
                <th>岗位</th>
                <th>器具名称</th>
                <th>规格型号</th>
            </tr>
            </thead>
            <tbody>
            @foreach($certificates as $certificate)
                <tr>
                    <td style="width:5%;">
                        <div class="styled-checkbox">
                            <input type="checkbox" name="cb[{{$certificate->order}}]" id="cb[{{$certificate->order}}]"
                                   @if(isset(array_flip($settings)[$certificate->order])) checked @endif>
                            <label for="cb[{{$certificate->order}}]"></label>
                        </div>
                    </td>
                    <td>{{$certificate->order}}</td>
                    <td>{{$certificate->position}}</td>
                    <td>{{$certificate->instrument}}</td>
                    <td>{{$certificate->model}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
