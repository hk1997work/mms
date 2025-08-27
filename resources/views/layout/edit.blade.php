<div class="off-sidebar-container">
    <ul class="nav nav-tabs" role="tablist">
        @hasSection('content_title')
            @yield('content_title')
            <div class="sidebar-btn" hidden></div>
        @else
            <li class="nav-item">
                <button class="nav-link active sidebar-btn" data-bs-toggle="tab"></button>
            </li>
        @endif
    </ul>
    <div class="off-sidebar-content auto-scroll">
        <form action="" onsubmit="return false;">
            {{method_field("put")}}
            {{csrf_field()}}
            @yield('content_form')
            <div class="position-fixed bottom-0 end-0 p-3">
                <button class="btn btn-outline-primary submit-edit sidebar-url">确 定</button>
                <button class="btn btn-outline-secondary sidebar-close">返 回</button>
            </div>
        </form>
    </div>
</div>
