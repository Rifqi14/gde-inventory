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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', 'Admin\DashboardController@index')->name('login');
    Route::get('/', 'Admin\DashboardController@index')->name('dashboard.index');
    //Route User
    Route::get('/user/read', 'Admin\UserController@read')->name('user.read');
    Route::get('/user/select', 'Admin\UserController@select')->name('user.select');
    Route::get('/user/spv-read', 'Admin\UserController@supervisor_read')->name('user.spv_read');
    Route::resource('/user', 'Admin\UserController');
    //Route Role
    Route::get('/role/read', 'Admin\RoleController@read')->name('role.read');
    Route::get('/role/select', 'Admin\RoleController@select')->name('role.select');
    Route::resource('/role', 'Admin\RoleController');
    //Route Menu
    Route::get('/menu/select', 'Admin\MenuController@select')->name('menu.select');
    Route::post('/menu/order', 'Admin\MenuController@order')->name('menu.order');
    Route::resource('/menu', 'Admin\MenuController')->only(['index', 'store', 'edit', 'update', 'destroy']);
    //Route Role
    Route::get('/role/set/{id}', 'Admin\RoleController@set')->name('role.set');
    Route::get('/role/read', 'Admin\RoleController@read')->name('role.read');
    Route::get('/role/select', 'Admin\RoleController@select')->name('role.select');
    Route::get('/role/selecttitle', 'Admin\RoleController@selecttitle')->name('role.selecttitle');
    Route::resource('/role', 'Admin\RoleController');
    //Route Role Menu
    Route::post('/rolemenu/update', 'Admin\RoleMenuController@update')->name('rolemenu.update');
    //Route Vehicle
    Route::get('/vehicle/read', 'Admin\VehicleController@read')->name('vehicle.read');
    Route::get('/vehicle/select', 'Admin\VehicleController@select')->name('vehicle.select');
    Route::resource('/vehicle', 'Admin\VehicleController');
    //Route Site
    Route::get('/site/read', 'Admin\SiteController@read')->name('site.read');
    Route::get('/site/select', 'Admin\SiteController@select')->name('site.select');
    Route::resource('/site', 'Admin\SiteController');
});
