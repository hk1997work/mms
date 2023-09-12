<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => '\App\Http\Controllers'], function () {
    Route::group(['middleware' => 'auth:web'], function () {
        //首页
        Route::get('/', 'MainController@index');
        Route::get('/main_ajax', 'MainController@ajax');

        //计量台账
        Route::group(['middleware' => 'can:certificate'], function () {
            //证书管理
            Route::resource('/certificate', 'CertificateController');
            Route::resource('/active', 'CertificateController');
            Route::resource('/deactive', 'CertificateController');
            Route::resource('/scrap', 'CertificateController');
            Route::resource('/invalid', 'CertificateController');
            //证书操作
            Route::get('/replace/{certificate}/edit', 'CertificateController@replace');
            Route::put('/replace/{certificate}', 'CertificateController@updateReplace');
            Route::put('/apply/{certificate}', 'CertificateController@apply');
            Route::put('/displace/{old}/{new}/{cause}', 'CertificateController@displace');
            Route::any('/pdf', 'Controller@pdf');
            //导出台账
            Route::resource('/export', 'ExportController');
        });

        //工作内容
        Route::group(['middleware' => 'can:work'], function () {
            //符合验证
            Route::resource('/confirm', 'ConfirmController');
            //打印标签
            Route::resource('/print', 'PrintController');
            //监督检查
            Route::resource('/supervision', 'SupervisionController');
            //周检通知
            Route::resource('/cyclical', 'CyclicalController');
            //抽检记录
            Route::resource('/sample', 'SampleController');
        });

        //检定机构
        Route::group(['middleware' => 'can:organization'], function () {
            //南京市
            Route::resource('/nanjing', 'NanjingController');
            Route::get('/nj/number/{factory_id}', 'nanjingController@number');
            //江苏省
            Route::resource('/jiangsu', 'JiangsuController');
            Route::get('/js/number/{factory_id}', 'jiangsuController@number');
        });

        //计量管理
        Route::group(['middleware' => 'can:manage'], function () {
            //岗位管理
            Route::resource('/position', 'PositionController');
            Route::post('/move/position/{position}/{type}', 'PositionController@move');
            Route::post('/sign/position/{position}', 'PositionController@sign');
            Route::get('/sn/{certificate}/edit', 'PositionController@sn');
            Route::put('/sn/{certificate}', 'PositionController@updateSn');
            Route::post('/move/sn/{certificate}/{type}', 'PositionController@moveSn');
            //量具管理
            Route::resource('/tool', 'ToolController');
            //厂家管理
            Route::resource('/factory', 'FactoryController');
            //编号管理
            Route::resource('/number', 'NumberController');
            //标准管理
            Route::resource('/standard', 'StandardController');
            //数据验证
            Route::resource('/check', 'CheckController');
        });

        //系统设置
        Route::group(['middleware' => 'can:setting'], function () {
            //用户管理
            Route::resource('/user', 'UserController');
            Route::get('/roles/{user}/edit', 'UserController@role');
            Route::put('/roles/{user}', 'UserController@storeRole');
            //角色管理
            Route::resource('/role', 'RoleController');
            Route::get('/permissions/{role}/edit', 'RoleController@permission');
            Route::put('/permissions/{role}', 'RoleController@storePermission');
            //权限管理
            Route::resource('/permission', 'PermissionController');
            Route::post('/move/permission/{permission}/{type}', 'PermissionController@move');
            //参数管理
            Route::resource('/parameter', 'ParameterController');
            Route::post('/move/parameter/{parameter}/{type}', 'ParameterController@move');
        });

        //文件下载
        Route::get('/download/{extension}/{path}/{id}/{filename}', function ($extension, $path, $id, $filename) {
            return response()->download(storage_path("app\public\\$path\\$id.$extension"), "$filename.$extension");
        });

        //API
        //证书参数
        Route::any('/certificate_standard', 'Controller@getStandard');
        Route::get('/certificate_sn/{position_id}', 'Controller@getSn');
        Route::get('/certificate_info/{tool}', 'Controller@getInfo');
        Route::get('/certificate_factories/{tool_id}', 'Controller@getFactories');
        Route::get('/certificate_numbers/{factory_id}', 'Controller@getNumbers');
        Route::get('/certificate_no/{certificate_no}', 'Controller@getCertificateNo');
        Route::get('/certificate_number/{number}', 'Controller@getNumber');
    });
    //登录页面
    Route::get('/login', 'LoginController@index')->name('login');
    //登录行为
    Route::post('/login', 'LoginController@login');
    //登出行为
    Route::get('/logout', 'LoginController@logout');

});
