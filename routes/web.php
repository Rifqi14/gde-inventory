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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});
Route::get('admin/error', function () {
    return view('admin.error.index');
});
Auth::routes();

Route::get('/test', 'Admin\TestController@test')->name('test');

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
        // Route Request Vehicle
        Route::get('/requestvehicle/read','Admin\RequestVehicleController@read')->name('requestvehicle.read');
        Route::get('/requestvehicle/select','Admin\RequestVehicleController@select')->name('requestvehicle.select');
        Route::get('/requestvehicle/delete/{id}','Admin\RequestVehicleController@delete');
        Route::get('/requestvehicle/edit/{id}','Admin\RequestVehicleController@edit');
        Route::get('/requestvehicle/daterequest','Admin\RequestVehicleController@daterequest')->name('requestvehicle.daterequest');
        Route::resource('/requestvehicle','Admin\RequestVehicleController');
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
        Route::get('/contract/product', 'Admin\ContractController@product')->name('contract.product');
        Route::get('/contract/selectproduct', 'Admin\ContractController@selectproduct')->name('contract.selectproduct');
        Route::get('/contract/selectbatch', 'Admin\ContractController@selectbatch')->name('contract.selectbatch');
        Route::post('/contract/storeproduct', 'Admin\ContractController@storeproduct')->name('contract.product.store');
        Route::post('/contract/deleteproduct', 'Admin\ContractController@deleteproduct')->name('contract.product.delete');
        Route::post('/contract/updateproduct', 'Admin\ContractController@updateproduct')->name('contract.product.update');
        Route::get('/contract/product/show', 'Admin\ContractController@showproduct')->name('contract.product.show');
        Route::get('/contract/product/read', 'Admin\ContractController@productread')->name('contract.product.read');
        Route::get('/contract/batch/read', 'Admin\ContractController@batchread')->name('contract.batch.read');
        Route::post('/contract/batch/add', 'Admin\ContractController@batchadd')->name('contract.batch.add');
        Route::post('/contract/batch/delete', 'Admin\ContractController@batchdelete')->name('contract.batch.delete');
        Route::get('/contract/batch/edit', 'Admin\ContractController@batchedit')->name('contract.batch.edit');
        Route::get('/contract/batch/show', 'Admin\ContractController@batchshow')->name('contract.batch.show');
        Route::post('/contract/batch/update', 'Admin\ContractController@batchupdate')->name('contract.batch.update');
        Route::get('/contract/batch/product/read', 'Admin\ContractController@batchproductread')->name('contract.batch.product.read');
        Route::post('/contract/batch/product/add', 'Admin\ContractController@batchproductadd')->name('contract.batch.product.add');
        Route::post('/contract/batch/product/delete', 'Admin\ContractController@batchproductdelete')->name('contract.batch.product.delete');
        Route::get('/contract/batch/{id}', 'Admin\ContractController@batch')->name('contract.batch');
        Route::resource('/contract', 'Admin\ContractController');
        //Route Purchasing
        Route::get('/purchasing/read', 'Admin\PurchasingController@read')->name('purchasing.read');
        Route::get('/purchasing/select', 'Admin\PurchasingController@select')->name('purchasing.select');
        Route::get('/purchasing/getgdeperiod', 'Admin\PurchasingController@getgdeperiod')->name('purchasing.getgdeperiod');
        Route::get('/purchasing/getadb', 'Admin\PurchasingController@getadb')->name('purchasing.getadb');
        Route::get('/purchasing/getperiod', 'Admin\PurchasingController@getperiod')->name('purchasing.getperiod');
        Route::post('/purchasing/addnotes', 'Admin\PurchasingController@addnotes')->name('purchasing.addnotes');
        Route::get('/purchasing/test', 'Admin\PurchasingController@test');
        Route::resource('/purchasing', 'Admin\PurchasingController');
        // Route Warehouse
        Route::get('/warehouse/read', 'Admin\WarehouseController@read')->name('warehouse.read');
        Route::get('/warehouse/select', 'Admin\WarehouseController@select')->name('warehouse.select');
        Route::resource('/warehouse', 'Admin\WarehouseController');
        // Route Rack
        Route::get('/rack/select', 'Admin\RackController@select')->name('rack.select');
        Route::get('/rack/read', 'Admin\RackController@read')->name('rack.read');
        Route::resource('/rack', 'Admin\RackController');
        // Route Bin
        Route::get('/bin/read', 'Admin\BinController@read')->name('bin.read');
        Route::resource('/bin', 'Admin\BinController');
        // Route Product
        Route::get('/product/read', 'Admin\ProductController@read')->name('product.read');
        Route::get('/product/select','Admin\ProductController@select')->name('product.select');
        Route::resource('/product','Admin\ProductController');
        // Route Province
        Route::get('/province/select', 'Admin\ProvinceController@select')->name('province.select');
        // Route Region
        Route::get('/region/select', 'Admin\RegionController@select')->name('region.select');
        // Route District
        Route::get('/district/select', 'Admin\DistrictController@select')->name('district.select');
        // Route Village
        Route::get('/village/select', 'Admin\VillageController@select')->name('village.select');
        //Route Business Trip
        Route::get('/businesstrip/read', 'Admin\BusinessTripController@read')->name('businesstrip.read');
        Route::get('/businesstrip/select', 'Admin\BusinessTripController@select')->name('businesstrip.select');
        Route::get('/businesstrip/delete/{id}','Admin\BusinessTripController@destroy');        
        Route::get('/businesstrip/update/{id}','Admin\BusinessTripControlller@update');
        Route::get('/businesstrip/edit/{id}','Admin\BusinessTripController@edit');        
        Route::resource('/businesstrip', 'Admin\BusinessTripController');
        // Route Product Category
        Route::get('/productcategory/read','Admin\ProductCategoryController@read')->name('productcategory.read');  
        Route::get('/productcategory/select','Admin\ProductCategoryController@select')->name('productcategory.select');        
        Route::get('/productcategory/parentcategories','Admin\ProductCategoryController@parentcategories')->name('productcategory.parentcategories');        
        Route::get('/productcategory/edit/{id}', 'Admin\ProductCategoryController@edit');                
        Route::get('/productcategory/delete/{id}','Admin\ProductCategoryController@destroy')->name('productcategory.delete');
        Route::resource('/productcategory','Admin\ProductCategoryController');
        // Route Doc Category
        Route::get('/documentcategory/read','Admin\DcCategoryController@read')->name('documentcategory.read');  
        Route::resource('/documentcategory','Admin\DcCategoryController');

        // Route UOM Category
        Route::get('/uomcategory/read','Admin\UomCategoryController@read')->name('uomcategory.read');
        Route::get('/uomcategory/select','Admin\UomCategoryController@select')->name('uomcategory.select');        
        Route::get('/uomcategory/delete/{id}','Admin\UomCategoryController@destroy');
        Route::get('/uomcategory/edit/{id}','Admin\UomCategoryController@edit');
        Route::resource('/uomcategory','Admin\UomCategoryController');

        // Route UOM
        Route::get('/uom/read','Admin\UomController@read')->name('uom.read');
        Route::get('/uom/select','Admin\UomController@select')->name('uom.select');
        Route::resource('/uom','Admin\UomController');

        // Route Employee
        Route::get('/employee/read','Admin\EmployeeController@read')->name('employee.read');  
        Route::get('/employee/select','Admin\EmployeeController@select')->name('employee.select');
        Route::get('/employee/edit/{id}','Admin\EmployeeController@edit');        
        Route::get('/employee/detail/{id}','Admin\EmployeeController@detail');     
        Route::get('/employee/delete/{id}','Admin\EmployeeController@destroy');        
        Route::resource('/employee','Admin\EmployeeController');
        // Master Working Calendar
        Route::get('/calendar/read', 'Admin\CalendarController@read')->name('calendar.read');
        Route::get('/calendar/select', 'Admin\CalendarController@select')->name('calendar.select');
        Route::get('/calendar/{id}/show', 'Admin\CalendarController@show')->name('calendar.show');
        Route::resource('/calendar', 'Admin\CalendarController');

        // Calendar Exception
        Route::get('/calendarexception/read', 'Admin\CalendarExceptionController@read')->name('calendarexception.read');
        Route::get('/calendarexception/select', 'Admin\CalendarExceptionController@select')->name('calendarexception.select');
        Route::get('/calendarexception/{id}/calendar', 'Admin\CalendarExceptionController@calendar')->name('calendarexception.calendar');
        Route::post('/calendarexception/addcalendar', 'Admin\CalendarExceptionController@addcalendar')->name('calendarexception.addcalendar');
        Route::resource('/calendarexception', 'Admin\CalendarExceptionController');

        // Attendance Machine
        Route::get('/attendancemachine/read', 'Admin\AttendanceMachineController@read')->name('attendancemachine.read');
        Route::get('/attendancemachine/select', 'Admin\AttendanceMachineController@select')->name('attendancemachine.select');
        Route::resource('/attendancemachine', 'Admin\AttendanceMachineController');

        // Receipt Document
        Route::get('/contractreceipt/selectcontract', 'Admin\ContractReceiptController@selectcontract')->name('contractreceipt.selectcontract');
        Route::get('/contractreceipt/selectbatch', 'Admin\ContractReceiptController@selectbatch')->name('contractreceipt.selectbatch');
        Route::get('/contractreceipt/read', 'Admin\ContractReceiptController@read')->name('contractreceipt.read');
        Route::resource('/contractreceipt', 'Admin\ContractReceiptController');

        // Product Serial
        Route::resource('/productserial', 'Admin\ProductSerialController');

        // Goods Receipt
        Route::resource('/goodsreceipt', 'Admin\GoodsReceiptController');

        // Attendance
        Route::get('/attendance/read', 'Admin\AttendanceController@read')->name('attendance.read');
        Route::resource('/attendance', 'Admin\AttendanceController');

        // Product Borrowing        
        Route::get('/productborrowing/read','Admin\ProductBorrowingController@read')->name('productborrowing.read');
        Route::resource('/productborrowing','Admin\ProductBorrowingController');
    });
});