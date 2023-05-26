<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">标准使用情况</h4>
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">×</span>
                <span class="sr-only">close</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive" style="height:500px;">
                <table id="modal-table" class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th>器具名称</th>
                        <th>规格型号</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tools as $tool)
                        <tr>
                            <td>{{$tool->instrument}}</td>
                            <td>{{$tool->model}}</td>
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
