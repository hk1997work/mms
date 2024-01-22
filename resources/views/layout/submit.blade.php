<div class="off-sidebar-container">
    <header class="off-sidebar-header">
        <ul class="button-nav nav nav-tabs mt-3 mb-3 ml-4" role="tablist">
            <li><a class="active sidebar-btn" data-toggle="tab" role="tab"></a></li>
        </ul>
    </header>
    <div class="off-sidebar-content offcanvas-scroll auto-scroll">
        <form action="" method="post" enctype="multipart/form-data">
            {{method_field("put")}}
            {{csrf_field()}}
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane show active fade">
                    @yield('content_form')
                    <input type="hidden" class="sidebar-url" name="sidebar-url" value="">
                    <div class="enter-message">
                        <button class="btn btn-outline-primary ripple btn-submit" type="submit">确 定</button>
                        <a class="btn btn-outline-secondary ripple sidebar-close" href="#">取 消</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
