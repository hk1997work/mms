@extends('layout.create')
@section('content_form')
    <div class="col-12">
        <table id="off-sidebar-table" class="table table-hover table-data">
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
                <tr class="{{ in_array($certificate->order, $settings) ? 'selected' : '' }}">
                    <td>{{$certificate->order}}</td>
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