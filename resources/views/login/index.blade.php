<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>计量管理系统</title>

    <!-- 网站图标 -->
    <link rel="apple-touch-icon" sizes="180x180" href="/admin/assets/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/admin/assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/admin/assets/img/favicon-16x16.png">
    <!-- CSS样式 -->
    <link rel="stylesheet" href="/admin/assets/vendors/css/base/bootstrap.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/base/elisyam-1.2.css">

</head>
<body class="bg-white">
<div class="container-fluid no-padding h-100">
    <div class="row flex-row h-100">
        <div class="col-xl-9 col-lg-8 col-md-7 no-padding d-none d-sm-table-cell">
            <div class="elisyam-bg background-01">
                <div class="elisyam-overlay overlay-01"></div>
                <div class="authentication-col-content mx-auto">
                    <h1>计 量 管 理 系 统</h1>
                    <span class="description"></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-5 my-auto no-padding">
            <!-- Begin Form -->
            <div class="authentication-form mx-auto">
                <div class="logo-centered">
                    <img src="/admin/assets/img/logo.png" alt="logo">
                </div>
                <form action="/login" method="post">
                    {{csrf_field()}}
                    <div class="group material-input">
                        <input type="text" name="username" required>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>用户名</label>
                    </div>
                    <div class="group material-input">
                        <input type="password" name="password" required>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>密码</label>
                    </div>
                    @foreach($errors->all() as $error)
                        <div class="text-danger">{{$error}}</div>
                    @endforeach
                    <div class="sign-btn text-center">
                        <button type="submit" class="btn btn-lg btn-gradient-01">登 录</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
