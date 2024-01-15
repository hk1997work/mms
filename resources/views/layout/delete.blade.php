<div class="off-sidebar-container">
    <header class="off-sidebar-header">
        <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-4" role="tablist">
            <li><a class="active sidebar-btn" data-toggle="tab" role="tab"></a></li>
        </ul>
    </header>
    <div class="off-sidebar-content offcanvas-scroll auto-scroll">
        <form action="" onsubmit="return false;">
            {{method_field("delete")}}
            {{csrf_field()}}
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane show active fade">
                    <input type="hidden" class="sidebar-url" name="sidebar-url" value="">
                    <div class="enter-message">
                        <button class="btn btn-outline-danger ripple submit-delete">删 除</button>
                        <button class="btn btn-outline-secondary ripple sidebar-close">取 消</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
