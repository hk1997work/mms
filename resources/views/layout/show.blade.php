<div class="off-sidebar-container">
    <header class="off-sidebar-header">
        <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-4" role="tablist">
            @yield('content_title')
        </ul>
    </header>
    <div class="off-sidebar-content offcanvas-scroll auto-scroll">
        <input type="hidden" class="sidebar-url" name="sidebar-url" value="">
        <div hidden class="sidebar-btn"></div>
        <div class="tab-content">
            @yield('content_form')
        </div>
    </div>
</div>
