<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.85, maximum-scale=0.85, user-scalable=no">
    <title>计量管理系统</title>
    <!-- 网站图标 -->
    <link rel="apple-touch-icon" sizes="180x180" href="/admin/assets/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/admin/assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/admin/assets/img/favicon-16x16.png">
    <!-- CSS样式 -->
    <link rel="stylesheet" href="/admin/assets/vendors/css/base/bootstrap.min.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/base/elisyam-1.2.min.css">
    <link rel="stylesheet" href="/admin/assets/css/animate/animate.min.css">
    @stack('page-css')
</head>
<body id="page-top">
<!-- 开始 加载 -->
<div id="preloader">
    <div class="canvas">
        <img src="/admin/assets/img/logo.png" alt="logo" class="loader-logo">
        <div class="spinner"></div>
    </div>
</div>
<!-- 结束 加载 -->
<!-- 开始 主体 -->
<div class="page">
    <!-- 开始 头部 -->
    <header class="header">
        <nav class="navbar fixed-top">
            <!-- 开始 标题 -->
            <div class="navbar-holder d-flex align-items-center align-middle justify-content-between">
                <!-- 开始 图标 -->
                <div class="navbar-header">
                    <a href="/" class="navbar-brand">
                        <div class="brand-image brand-big">
                            <img src="/admin/assets/img/logo-big.png" alt="logo" class="logo-big">
                        </div>
                        <div class="brand-image brand-small">
                            <img src="/admin/assets/img/logo.png" alt="logo" class="logo-small">
                        </div>
                    </a>
                    <!-- 开始 切换键 -->
                    <a id="toggle-btn" href="#" class="menu-btn active">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                    <!-- 结束 切换键 -->
                </div>
                <!-- 结束 图标 -->
                <!-- 开始 导航栏菜单 -->
                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center pull-right">
                    <!-- 开始 通知 -->
                    <li class="nav-item dropdown"><a id="notifications" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link"><i class="la la-bell animated infinite swing"></i><span class="badge-pulse"></span></a>
                        <ul aria-labelledby="notifications" class="dropdown-menu notification">
                            <li>
                                <div class="notifications-header">
                                    <div class="title">Notifications (4)</div>
                                    <div class="notifications-overlay"></div>
                                    <img src="/admin/assets/img/notifications/01.jpg" alt="..." class="img-fluid">
                                </div>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="message-icon">
                                        <i class="la la-user"></i>
                                    </div>
                                    <div class="message-body">
                                        <div class="message-body-heading">
                                            New user registered
                                        </div>
                                        <span class="date">2 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="message-icon">
                                        <i class="la la-calendar-check-o"></i>
                                    </div>
                                    <div class="message-body">
                                        <div class="message-body-heading">
                                            New event added
                                        </div>
                                        <span class="date">7 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="message-icon">
                                        <i class="la la-history"></i>
                                    </div>
                                    <div class="message-body">
                                        <div class="message-body-heading">
                                            Server rebooted
                                        </div>
                                        <span class="date">7 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="message-icon">
                                        <i class="la la-twitter"></i>
                                    </div>
                                    <div class="message-body">
                                        <div class="message-body-heading">
                                            You have 3 new followers
                                        </div>
                                        <span class="date">10 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a rel="nofollow" href="#" class="dropdown-item all-notifications text-center">View All Notifications</a>
                            </li>
                        </ul>
                    </li>
                    <!-- 结束 通知 -->
                    <!-- 开始 用户 -->
                    <li class="nav-item dropdown"><a rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link"><img src="/admin/assets/img/avatar/avatar-01.jpg" alt="..." class="avatar rounded-circle"></a>
                        <ul aria-labelledby="user" class="user-size dropdown-menu">
                            <li class="welcome">
                                <a href="#" class="edit-profil"><i class="la la-gear"></i></a>
                                <img src="/admin/assets/img/avatar/avatar-01.jpg" alt="..." class="rounded-circle">
                            </li>
                            <li>
                                <a href="pages-profile.html" class="dropdown-item">
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="app-mail.html" class="dropdown-item">
                                    Messages
                                </a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-item no-padding-bottom">
                                    Settings
                                </a>
                            </li>
                            <li class="separator"></li>
                            <li>
                                <a href="pages-faq.html" class="dropdown-item no-padding-top">
                                    Faq
                                </a>
                            </li>
                            <li><a rel="nofollow" href="/logout" class="dropdown-item logout text-center"><i class="ti-power-off"></i></a></li>
                        </ul>
                    </li>
                    <!-- 结束 用户 -->
                    <!-- 开始 快捷栏 -->
                    <li class="nav-item"><a href="#off-canvas" class="open-sidebar"><i class="la la-ellipsis-h"></i></a>
                    </li>
                    <!-- 结束 快捷栏 -->
                </ul>
                <!-- 结束 导航栏菜单 -->
            </div>
            <!-- 结束 标题 -->
        </nav>
    </header>
    <!-- 结束 头部 -->
    <!-- 开始 网页内容 -->
    <div class="page-content d-flex align-items-stretch">
        <!-- 开始 左侧边栏 -->
        <div class="default-sidebar">
            <nav class="side-navbar box-scroll sidebar-scroll">
                <ul class="list-unstyled">
                    <li><a href="/"><i class="la la-home"></i><span>主页</span></a></li>
                    @foreach($permissions->where('level',1) as $permission)
                        @can($permission->name)
                            <li><a href="#dropdown-{{$permission->name}}" aria-expanded="false" data-toggle="collapse"><i class="{{$permission->icon}}"></i><span>{{$permission->description}}</span></a>
                                <ul id="dropdown-{{$permission->name}}" class="collapse list-unstyled pt-0 nav-tabs">
                                    @foreach($permissions->where('pid',$permission->id) as $p)
                                        @can($p->name)
                                            <li><a href="/{{$p->name}}" id="{{$p->name}}">{{$p->description}}</a></li>
                                        @endcan
                                    @endforeach
                                </ul>
                            </li>
                        @endcan
                    @endforeach
                </ul>
            </nav>
        </div>
        <!-- 结束 左侧边栏 -->
        <!-- 开始 页面内容 -->
        <div class="content-inner">
            <!-- 开始 容器 -->
            <div class="container-fluid">
                @yield("content")
            </div>
            <!-- 结束 容器 -->
            <a href="#" class="go-top"><i class="la la-arrow-up"></i></a>
        </div>
        <!-- 结束 页面内容 -->
    </div>
    <!-- 结束 网页内容 -->
</div>
<!-- 结束 主体 -->
<!-- 开始 模态框 -->
<div id="modal" class="modal fade" data-backdrop="static"></div>
<!-- 结束 模态框 -->
<div class="off-sidebar from-left" data-pos="left"></div>
<div class="off-sidebar from-right" data-pos="right"></div>
<div class="off-sidebar from-up" data-pos="up"></div>
<script>
    const csrf_token = '{{csrf_token()}}'
    const menu = '{{$menu}}'
</script>

@stack('page-js-before')

<!-- 开始 底层Js -->
<script src="/admin/assets/vendors/js/base/jquery.min.js"></script>
<script src="/admin/assets/vendors/js/base/jquery.base64.js?v=1.0"></script>
<script src="/admin/assets/vendors/js/base/jquery.cookie.js"></script>
<script src="/admin/assets/vendors/js/base/aes.js?v=1.0"></script>
<script src="/admin/assets/vendors/js/base/core.min.js"></script>
<!-- 结束 底层Js -->

<!-- 开始 插件Js -->
<script src="/admin/assets/vendors/js/nicescroll/nicescroll.min.js"></script>
<script src="/admin/assets/vendors/js/noty/noty.min.js"></script>
<script src="/admin/assets/vendors/js/app/app.js"></script>
<!-- 结束 插件Js -->

@stack('page-js-after')

</body>
</html>


