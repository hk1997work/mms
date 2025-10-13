<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.85, maximum-scale=0.85, user-scalable=no">
    <title>计量管理系统</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/admin/assets/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/admin/assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/admin/assets/img/favicon-16x16.png">

    <link rel="stylesheet" href="/admin/assets/vendors/css/base/bootstrap.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/animate/animate.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/bootstrap-select/bootstrap-select.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/vis-timeline/vis-timeline-graph2d.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/swiper/swiper-bundle.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/datatables/datatables.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/datatables/fixedColumns.dataTables.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/datatables/scroller.dataTables.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/datepicker/daterangepicker.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/noty/noty.css">
    <link rel="stylesheet" href="/admin/assets/icons/css/all.min.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/base/style.css">
    @stack('page-css')
</head>
<body>

<div id="preloader">
    <div class="canvas">
        <img src="/admin/assets/img/logo.png" alt="logo" class="loader-logo">
        <div class="spinner"></div>
    </div>
</div>

<header>
    <nav class="navbar navbar-expand-sm bg-white fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">主页</a>
                    </li>
                    @foreach($permissions->where('level',1) as $permission)
                        @can($permission->name)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{$permission->description}}
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach($permissions->where('pid',$permission->id) as $p)
                                        @can($p->name)
                                            <li><a class="dropdown-item" href="/{{$p->name}}" id="{{$p->name}}">{{$p->description}}</a></li>
                                        @endcan
                                    @endforeach
                                </ul>
                            </li>
                        @endcan
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>
</header>
<main class="container-fluid">
    @yield("content")
</main>

<div class="off-sidebar from-left" data-pos="left"></div>
<div class="off-sidebar from-right" data-pos="right"></div>
<div class="off-sidebar from-up" data-pos="up"></div>
<script>
    const csrf_token = '{{csrf_token()}}'
    const menu = '{{$menu}}'
</script>

@stack('page-js-before')

<script src="/admin/assets/vendors/js/base/jquery.min.js"></script>
<script src="/admin/assets/vendors/js/base/jquery.base64.js"></script>
<script src="/admin/assets/vendors/js/base/jquery.cookie.js"></script>
<script src="/admin/assets/vendors/js/base/aes.js"></script>
<script src="/admin/assets/vendors/js/base/core.js"></script>
<script src="/admin/assets/vendors/js/base/bootstrap.bundle.js"></script>

<script src="/admin/assets/vendors/js/datepicker/moment-with-locales.js"></script>
<script src="/admin/assets/vendors/js/noty/noty.js"></script>
<script src="/admin/assets/vendors/js/bootstrap-select/bootstrap-select.js"></script>
<script src="/admin/assets/vendors/js/chart/chart.js"></script>
<script src="/admin/assets/vendors/js/datatables/datatables.js"></script>
<script src="/admin/assets/vendors/js/datatables/dataTables.bootstrap5.js"></script>
<script src="/admin/assets/vendors/js/datatables/dataTables.fixedColumns.js"></script>
<script src="/admin/assets/vendors/js/datatables/dataTables.scroller.js"></script>
<script src="/admin/assets/vendors/js/datatables/dataTables.select.js"></script>
<script src="/admin/assets/vendors/js/datepicker/daterangepicker.js"></script>
<script src="/admin/assets/vendors/js/swiper/swiper-bundle.js"></script>
<script src="/admin/assets/vendors/js/vis-timeline/vis-timeline-graph2d.js"></script>
<script src="/admin/assets/vendors/js/progress/circle-progress.js"></script>

<script src="/admin/assets/js/app/app.js"></script>
<script src="/admin/assets/js/components/datepicker/datepicker.js"></script>
<script src="/admin/assets/js/components/tables/tables.js"></script>
<script>
    moment.locale('zh-cn');
</script>
@stack('page-js-after')

</body>
</html>


