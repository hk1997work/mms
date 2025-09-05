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
        <form action="" @if(($class??'')=='btn-submit') method="post" enctype="multipart/form-data" @else onsubmit="return false;" @endif>
            {{$method??''}}
            {{csrf_field()}}
            @yield('content_form')
            @if(isset($class))
                @include('template.btn',['color'=>$color??'primary','class'=>$class,'label'=>$label??'确 定'])
            @endif
        </form>
    </div>
</div>
