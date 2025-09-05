<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{$label}}<i class="ion-android-arrow-dropdown"></i></a>
<ul class="dropdown-menu dropdown-menu-end">
    @if(isset($parent))
        <a class='dropdown-item' href='?id={{$parent[$id]}}'>全部</a>
        @foreach($items as $item)
            <li><a class="dropdown-item" href="?id={{$parent[$id]}}&{{$path}}={{$item[$id]}}">{{$item->parent[$field]}}</a></li>
        @endforeach
    @else
        @foreach($items as $item)
            <li><a class="dropdown-item" href="?id={{$item[$id]}}">{{$item[$field]}}</a></li>
        @endforeach
    @endif
</ul>