<?php

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

# 首页 默认上传图片页面
Route::get('/', 'UploadController@index')->name('index');
# 接收上传内容
Route::post('upload', 'UploadController@store')->name('upload');

# 搜索相关
Route::get('search/{type}', 'SearchController@index');
Route::post('search', 'SearchController@store')->name('search');

# 关于我们
Route::get('about', 'AboutController@index');

# 登陆、登陆接收、登出
Route::get('login', 'SessionController@create')->name('login');
Route::post('login', 'SessionController@login');
Route::delete('logout', 'SessionController@destroy')->name('logout');

# 用户相关,个人中心,显示,修改
Route::resource('user', 'UserController');

# 用户绑定网盘相关
Route::get('my_cloud', 'UserCloudController@index')->name('my_cloud');
Route::post('my_cloud', 'UserCloudController@store')->name('my_cloud');

# 后台相关
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});