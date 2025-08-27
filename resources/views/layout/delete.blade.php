<div class="off-sidebar-container">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active sidebar-btn" data-bs-toggle="tab"></button>
        </li>
    </ul>
    <form action="" onsubmit="return false;">
        {{method_field("delete")}}
        {{csrf_field()}}
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane show active">
                <div class="position-fixed bottom-0 end-0 p-3">
                    <button class="btn btn-outline-danger submit-delete sidebar-url">删 除</button>
                    <button class="btn btn-outline-secondary sidebar-close">返 回</button>
                </div>
            </div>
        </div>
    </form>
</div>
