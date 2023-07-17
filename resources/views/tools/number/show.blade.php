<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{$state}}量具管理</h4>
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">×</span>
                <span class="sr-only">close</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive" style="max-height:500px;">
                <table id="unsorted-table" class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th>生产厂家</th>
                        <th>出厂编号</th>
                        <th>使用状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($numbers as $number)
                        <tr @if($number->certificate_id==null) class="bg-error" @endif>
                            <td>{{$number->factory}}</td>
                            <td @if($number->overtime) class="bg-error" @endif>{{$number->number}}</td>
                            <td @if($number->mistake) class="bg-error" @endif><span class="btn
                                @switch($number->state)
                                @case('在用') btn-outline-success @break
                                @case('备用') btn-outline-info @break
                                @case('待检') btn-outline-warning @break
                                @case('封存') btn-outline-primary @break
                                @case('损坏') btn-outline-danger @break
                                @case('报废') btn-outline-dark @break
                                @endswitch btn-sm ripple" onclick="m_show('certificate',{{$number->certificate_id}},1)">{{$number->state}}</span>
                            </td>
                            <td class="td-actions">
                                <a onclick="m_edit('number',{{$number->id}})"><i class="la la-edit edit"></i></a>
                                <a onclick="m_delete('number',{{$number->id}})"><i class="la la-close delete"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary ripple" data-dismiss="modal">返 回</button>
        </div>
    </div>
</div>
