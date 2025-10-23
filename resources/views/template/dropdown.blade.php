@php
    # $tmp_label
    # $tmp_items
    # $tmp_href
    # $tmp_field
    # $tmp_parent
    # $tmp_path
@endphp

<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{$tmp_label}}<i class="ion-android-arrow-dropdown"></i></a>
<ul class="dropdown-menu dropdown-menu-end">
    @if(isset($tmp_parent))
        <a class='dropdown-item' href='?id={{$tmp_parent[$tmp_href]}}'>全部</a>
        @foreach($tmp_items as $tmp_item)
            <li><a class="dropdown-item" href="?id={{$tmp_parent[$tmp_href]}}&{{$tmp_path}}={{$tmp_item[$tmp_href]}}">{{$tmp_item->parent[$tmp_field]}}</a></li>
        @endforeach
    @else
        @foreach($tmp_items as $tmp_item)
            <li><a class="dropdown-item" href="?id={{$tmp_item[$tmp_href]}}">{{$tmp_item[$tmp_field]}}</a></li>
        @endforeach
    @endif
</ul>