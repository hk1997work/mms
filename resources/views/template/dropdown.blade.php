<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{$label}}<i class="ion-android-arrow-dropdown"></i></a>
<ul class="dropdown-menu dropdown-menu-end">
    @foreach($items as $item)
        <li><a class="dropdown-item" href="?id={{$item[$id]}}">{{$item[$field]}}</a></li>
    @endforeach
</ul>