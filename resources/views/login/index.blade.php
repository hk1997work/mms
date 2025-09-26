<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>计量管理系统</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/admin/assets/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/admin/assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/admin/assets/img/favicon-16x16.png">

    <link rel="stylesheet" href="/admin/assets/vendors/css/base/bootstrap.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/base/elisyam-1.2.css">

</head>
<body>
<div class="container-fluid h-100">
    <div class="row flex-row h-100">
        <div class="col-xl-9 col-lg-8 col-md-7 d-none d-sm-table-cell my-auto">
            <h1>计 量 管 理 系 统</h1>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-5 my-auto">
            <form action="/login" method="post">
                {{csrf_field()}}
                @include('template.input',['tmp_name'=>'username','tmp_label'=>'用户名'])
                @include('template.input',['tmp_name'=>'password','tmp_label'=>'密码','tmp_type'=>'password'])
                @foreach($errors->all() as $error)
                    <div class="text-danger">{{$error}}</div>
                @endforeach
                <button type="submit" class="btn btn-outline-primary mt-3">登 录</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
