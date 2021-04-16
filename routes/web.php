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
    return redirect('/admin');
});
Route::get('admin/error', function () {
    return view('admin.error.index');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::get('/login', 'Auth\AdminLoginController@login')->name('admin.login.post');
    Route::get('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

    Route::group(['middleware' => ['auth:admin', 'page.admin']], function () {
        Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard.index');
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
        Route::get('/menu/json', 'Admin\MenuController@json')->name('menu.json');
        Route::resource('/menu', 'Admin\MenuController')->only(['index', 'store', 'edit', 'update', 'destroy']);
        //Route Role
        Route::get('/role/set/{id}', 'Admin\RoleController@set')->name('role.set');
        Route::get('/role/read', 'Admin\RoleController@read')->name('role.read');
        Route::get('/role/select', 'Admin\RoleController@select')->name('role.select');
        Route::get('/role/selecttitle', 'Admin\RoleController@selecttitle')->name('role.selecttitle');
        Route::resource('/role', 'Admin\RoleController');
        //Route Role Menu
        Route::post('/rolemenu/update', 'Admin\RoleMenuController@update')->name('rolemenu.update');
        //Route Site
        Route::get('/site/read', 'Admin\SiteController@read')->name('site.read');
        Route::get('/site/select', 'Admin\SiteController@select')->name('site.select');
        Route::get('/site/import', 'Admin\SiteController@import')->name('site.import');
        Route::post('/site/sync', 'Admin\SiteController@sync')->name('site.sync');
        Route::get('/site/restore/{id}', 'Admin\SiteController@restore')->name('site.restore');
        Route::get('/site/delete/{id}', 'Admin\SiteController@delete')->name('site.delete');
        Route::post('/site/preview', 'Admin\SiteController@preview')->name('site.preview');
        Route::post('/site/storemass', 'Admin\SiteController@storemass')->name('site.storemass');
        Route::post('/site/export', 'Admin\SiteController@export')->name('site.export');
        Route::resource('/site', 'Admin\SiteController');
        //Route Vehicle
        Route::get('/vehicle/read', 'Admin\VehicleController@read')->name('vehicle.read');
        Route::get('/vehicle/select', 'Admin\VehicleController@select')->name('vehicle.select');
        Route::resource('/vehicle', 'Admin\VehicleController');
        //Route Working Shift
        Route::get('/workingshift/read', 'Admin\WorkingShiftController@read')->name('workingshift.read');
        Route::get('/workingshift/select', 'Admin\WorkingShiftController@select')->name('workingshift.select');
        Route::resource('/workingshift', 'Admin\WorkingShiftController');
        //Route Budgetary
        Route::get('/budgetary/read', 'Admin\BudgetController@read')->name('budgetary.read');
        Route::get('/budgetary/select', 'Admin\BudgetController@select')->name('budgetary.select');
        Route::get('/budgetary/stack_chart', 'Admin\BudgetController@stack_chart')->name('budgetary.stack_chart');
        Route::get('/budgetary/show/{id}', 'Admin\BudgetController@showing');
        Route::get('/budgetary/edit/{id}', 'Admin\BudgetController@editing');
        Route::get('/budgetary/delete/{id}', 'Admin\BudgetController@destroy')->name('budgetary.delete');
        Route::resource('/budgetary', 'Admin\BudgetController');
        //Route Contract
        Route::get('/contract/read', 'Admin\ContractController@read')->name('contract.read');
        Route::resource('/contract', 'Admin\ContractController');
        //Route Purchasing
        Route::get('/purchasing/read', 'Admin\PurchasingController@read')->name('purchasing.read');
        Route::get('/purchasing/select', 'Admin\PurchasingController@select')->name('purchasing.select');
        Route::post('/purchasing/getgdeperiod', 'Admin\PurchasingController@getgdeperiod')->name('purchasing.getgdeperiod');
        Route::get('/purchasing/getadb', 'Admin\PurchasingController@getadb')->name('purchasing.getadb');
        Route::post('/purchasing/getperiod', 'Admin\PurchasingController@getperiod')->name('purchasing.getperiod');
        Route::resource('/purchasing', 'Admin\PurchasingController');
        //Route Business Trip
        Route::get('/business-trip/read', 'Admin\BusinessTripController@read')->name('bt.read');
        Route::get('/business-trip/select', 'Admin\BusinessTripController@select')->name('bt.select');
        Route::resource('/business-trip', 'Admin\BusinessTripController');
    });
});
