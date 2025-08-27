<div class="off-sidebar-container">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active sidebar-btn" data-bs-toggle="tab"></button>
        </li>
    </ul>
    <div class="off-sidebar-content auto-scroll">
        <form action="" onsubmit="return false;">
            {{csrf_field()}}
            @yield('content_form')
            <div class="position-fixed bottom-0 end-0 p-3">
                <button class="btn btn-outline-primary submit-add sidebar-url">确 定</button>
                <button class="btn btn-outline-secondary sidebar-close">返 回</button>
            </div>
        </form>
    </div>
</div>
