<div class="off-sidebar-container">
    <header class="off-sidebar-header">
        <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-4" role="tablist">
            @yield('content_title')
        </ul>
    </header>
    <div class="off-sidebar-content offcanvas-scroll auto-scroll">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane show active fade">
                <div class="sidebar-btn" hidden></div>
                @yield('content_form')
                <div class="enter-message">
                    <button class="btn btn-outline-secondary ripple sidebar-close sidebar-url">取 消</button>
                </div>
            </div>
        </div>
    </div>
</div>
