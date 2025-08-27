<div class="off-sidebar-container">
    <ul class="nav nav-tabs" role="tablist">
        @yield('content_title')
        <div class="sidebar-btn" hidden></div>
    </ul>
    <div class="off-sidebar-content auto-scroll">
        <div class="tab-content">
            <div class="tab-pane show active">
                @yield('content_form')
            </div>
        </div>
    </div>
</div>
