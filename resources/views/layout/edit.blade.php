<div class="off-sidebar-container">
    <header class="off-sidebar-header">
        <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-4" role="tablist">
            @hasSection('content_title'))
                @yield('content_title')
                <div class="sidebar-btn" hidden></div>
            @else
                <li><a class="active sidebar-btn" data-toggle="tab" role="tab"></a></li>
            @endif
        </ul>
    </header>
    <div class="off-sidebar-content offcanvas-scroll auto-scroll">
        <form action="" onsubmit="return false;">
            {{method_field("put")}}
            {{csrf_field()}}
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane show active fade">
                    @yield('content_form')
                    <div class="enter-message">
                        <button class="btn btn-outline-primary ripple submit-edit sidebar-url">确 定</button>
                        <button class="btn btn-outline-secondary ripple sidebar-close">取 消</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
