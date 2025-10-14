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
    <div class="off-sidebar-content auto-scroll table-responsive">
        @if(isset($tmp_class))
            <form action="" @if($tmp_class=='btn-submit') method="post" enctype="multipart/form-data" @else onsubmit="return false;" @endif>
                {{$tmp_method??''}}
                {{csrf_field()}}
                @yield('content_form')
                @include('template.sidebar-btn',['tmp_color'=>$tmp_color??'primary','tmp_class'=>$tmp_class,'tmp_label'=>$tmp_label??'确 定'])
            </form>
        @else
            @yield('content_form')
        @endif
    </div>
</div>
