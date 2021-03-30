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
    Route::get('/', 'Admin\DashboardController@index')->name('dashboard.index');
    Route::get('/user/read', 'Admin\UserController@read')->name('user.read');
    Route::get('/user/select', 'Admin\UserController@select')->name('user.select');
    Route::resource('/user', 'Admin\UserController');
    //Route Menu
    Route::get('/menu/select', 'Admin\MenuController@select')->name('menu.select');
    Route::post('/menu/order', 'Admin\MenuController@order')->name('menu.order');
    Route::resource('/menu', 'Admin\MenuController')->only(['index', 'store', 'edit', 'update', 'destroy']);
});
