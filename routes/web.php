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
        Route::get('/main_ajax', 'MainController@list');
        Route::view('/delete', 'layout.delete');

        //计量台账
        Route::group(['middleware' => 'can:certificate'], function () {
            //证书管理
            Route::resource('/certificate', 'CertificateController');
            Route::resource('/active', 'CertificateController');
            Route::resource('/borrow', 'CertificateController');
            Route::resource('/deactive', 'CertificateController');
            Route::resource('/scrap', 'CertificateController');
            Route::resource('/invalid', 'CertificateController');
            Route::post('/ajax_certificate', 'CertificateController@list');
            Route::get('/download_certificate/{certificate}','CertificateController@download');
            Route::any('/pdf', 'Controller@pdf');
            //导出台账
            Route::resource('/export', 'ExportController');
            //打印标签
            Route::resource('/print', 'PrintController');
            //监督检查
            Route::resource('/supervision', 'SupervisionController');
        });

        //工作内容
        Route::group(['middleware' => 'can:work'], function () {
            //符合验证
            Route::resource('/confirm', 'ConfirmController');
            Route::post('/ajax_confirm', 'ConfirmController@list');
            //周检通知
            Route::resource('/cyclical', 'CyclicalController');
            //抽检记录
            Route::resource('/sample', 'SampleController');
        });

        //检定机构
        Route::group(['middleware' => 'can:organization'], function () {
            //南京市
            Route::resource('/nanjing', 'NanjingController');
            Route::post('/ajax_nanjing', 'NanjingController@list');
            Route::get('/download_nanjing/{nanjing}','NanjingController@download');
            Route::post('/ajax_nanjing_show', 'NanjingController@list_show');
            //江苏省
            Route::resource('/jiangsu', 'JiangsuController');
            Route::post('/ajax_jiangsu', 'JiangsuController@list');
            Route::get('/download_jiangsu/{jiangsu}','JiangsuController@download');
            Route::post('/ajax_jiangsu_show', 'JiangsuController@list_show');
        });

        //计量管理
        Route::group(['middleware' => 'can:manage'], function () {
            //岗位管理
            Route::resource('/position', 'PositionController');
            Route::post('/ajax_position', 'PositionController@list');
            Route::post('/ajax_position_show', 'PositionController@list_show');
            Route::post('/move/position/{position}/{type}', 'PositionController@move');
            Route::get('/sn/{certificate}/edit', 'PositionController@sn');
            Route::put('/sn/{certificate}', 'PositionController@updateSn');
            Route::post('/move/sn/{certificate}/{type}', 'PositionController@moveSn');
            //量具管理
            Route::resource('/tool', 'ToolController');
            Route::post('/ajax_tool', 'ToolController@list');
            Route::post('/ajax_tool_show', 'ToolController@list_show');
            //厂家管理
            Route::resource('/factory', 'FactoryController');
            //编号管理
            Route::resource('/number', 'NumberController');
            //标准管理
            Route::resource('/standard', 'StandardController');
            Route::post('/ajax_standard', 'StandardController@list');
            //数据验证
            Route::resource('/check', 'CheckController');
        });

        //系统设置
        Route::group(['middleware' => 'can:setting'], function () {
            //用户管理
            Route::resource('/user', 'UserController');
            Route::post('/ajax_user', 'UserController@list');
            Route::get('/roles/{user}/edit', 'UserController@role');
            Route::put('/roles/{user}', 'UserController@storeRole');
            //角色管理
            Route::resource('/role', 'RoleController');
            Route::post('/ajax_role', 'RoleController@list');
            Route::get('/permissions/{role}/edit', 'RoleController@permission');
            Route::put('/permissions/{role}', 'RoleController@storePermission');
            //权限管理
            Route::resource('/permission', 'PermissionController');
            Route::post('/ajax_permission', 'PermissionController@list');
            Route::post('/move/permission/{permission}/{type}', 'PermissionController@move');
            //参数管理
            Route::resource('/parameter', 'ParameterController');
            Route::post('/ajax_parameter', 'ParameterController@list');
            Route::post('/move/parameter/{parameter}/{type}', 'ParameterController@move');
        });

        //文件下载
        Route::get('/download/{extension}/{path}/{id}/{filename}', function ($extension, $path, $id, $filename) {
            return response()->download(storage_path("app\public\\$path\\$id.$extension"), "$filename.$extension");
        });

        //API
        //证书参数
        Route::get('/certificate_sn/{position_id}', 'Controller@getSn');
        Route::get('/certificate_info/{tool}', 'Controller@getInfo');
        Route::get('/certificate_factories/{tool_id}', 'Controller@getFactories');
        Route::get('/certificate_numbers/{factory_id}', 'Controller@getNumbers');
        Route::get('/certificate_number/{number}', 'Controller@getNumber');
    });
    //登录页面
    Route::get('/login', 'LoginController@index')->name('login');
    //登录行为
    Route::post('/login', 'LoginController@login');
    //登出行为
    Route::get('/logout', 'LoginController@logout');

});
