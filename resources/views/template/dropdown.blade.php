<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{$tmp_label}}<i class="ion-android-arrow-dropdown"></i></a>
<ul class="dropdown-menu dropdown-menu-end">
    @if(isset($tmp_parent))
        <a class='dropdown-item' href='?id={{$tmp_parent[$tmp_href_id]}}'>全部</a>
        @foreach($tmp_items as $tmp_item)
            <li><a class="dropdown-item" href="?id={{$tmp_parent[$tmp_href_id]}}&{{$tmp_path}}={{$tmp_item[$tmp_href_id]}}">{{$tmp_item->parent[$tmp_field]}}</a></li>
        @endforeach
    @else
        @foreach($tmp_items as $tmp_item)
            <li><a class="dropdown-item" href="?id={{$tmp_item[$tmp_href_id]}}">{{$tmp_item[$tmp_field]}}</a></li>
        @endforeach
    @endif
</ul>