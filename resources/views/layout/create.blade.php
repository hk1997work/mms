<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">@yield('text_modal-title')</h4>
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">×</span>
                <span class="sr-only">close</span>
            </button>
        </div>
        <form action="" onsubmit="return false;" id="form">
            {{csrf_field()}}
            <div class="modal-body">
                <div class="form-group row mb-3">
                    @yield('content_form')
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary ripple">确 定</button>
                <button type="button" class="btn btn-secondary ripple" data-dismiss="modal">取 消</button>
            </div>
        </form>
    </div>
</div>
