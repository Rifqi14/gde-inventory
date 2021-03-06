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

use App\Http\Resources\Transmittal\TransmittalProperties\OrganizationCodeCollection;
use App\Models\Transmittal\TransmittalProperties\TransmittalOrganizationCode;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});
Route::get('admin/error', function () {
    return view('admin.error.index');
});
Auth::routes();

Route::get('/test', 'Admin\TestController@test')->name('test');
Route::get('/businesstrip/rateprocess', 'Admin\BusinessTripController@rateprocess')->name('businesstrip.rateprocess');
Route::get('/reimbursement/rateprocess', 'Admin\ReimbursementController@rateprocess')->name('reimbursement.rateprocess');
Route::resource('/documentmail', 'Admin\DocumentCenterMailController');

Route::resource('/attendanceclock', 'Machine\IclockTranscationController');

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::get('/login', 'Auth\AdminLoginController@login')->name('admin.login.post');
    Route::get('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

    Route::group(['middleware' => ['auth:admin', 'page.admin']], function () {
        Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard.index');
        // Route Log Revise
        Route::get('/logrevise/read', 'Admin\LogReviseController@read')->name('logrevise.read');
        Route::resource('/logrevise', 'Admin\LogReviseController');
        //Route User
        Route::get('/user/read', 'Admin\UserController@read')->name('user.read');
        Route::post('/user/sync', 'Admin\UserController@sync')->name('user.sync');
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
        Route::get('/requestvehicle/edit/{id}','Admin\RequestVehicleController@edit');
        Route::get('/requestvehicle/daterequest','Admin\RequestVehicleController@daterequest')->name('requestvehicle.daterequest');
        Route::post('/requestvehicle/revise', 'Admin\RequestVehicleController@revise')->name('requestvehicle.revise');
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

        //Route Activitie
        Route::get('/activitie/scurve/dieng', 'Admin\ActivitieController@dieng')->name('scurve.dieng');
        Route::get('/activitie/scurve/patuha', 'Admin\ActivitieController@patuha')->name('scurve.patuha');
        Route::post('/activitie/scurve/chart', 'Admin\ActivitieController@chart')->name('scurve.chart');
        Route::post('/activitie/scurve/get_progress', 'Admin\ActivitieController@get_progress')->name('scurve.get_progress');
        Route::post('/activitie/updateact', 'Admin\ActivitieController@updateact')->name('activitie.updateact');
        Route::post('/activitie/destroyact', 'Admin\ActivitieController@destroyact')->name('activitie.destroyact');
        Route::post('/activitie/order', 'Admin\ActivitieController@order')->name('activitie.order');
        Route::post('/activitie/import', 'Admin\ActivitieController@import')->name('activitie.import');
        Route::post('/activitie/getdetail', 'Admin\ActivitieController@getDetail')->name('activitie.get.detail');
        Route::post('/activitie/add-type', 'Admin\ActivitieController@addType')->name('activitie.add.type');
        Route::resource('/activitie', 'Admin\ActivitieController');

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

        // Grievance
        Route::get('/grievance/report/read', 'Admin\GrievanceController@reportread')->name('grievance.report.read');
        Route::get('/grievance/read', 'Admin\GrievanceController@read')->name('grievance.read');
        Route::post('/grievance/update_status', 'Admin\GrievanceController@update_status')->name('grievance.update_status');
        Route::get('/grievance/report/{id}/edit', 'Admin\GrievanceController@edit_report')->name('grievance.report.edit');
        Route::get('/grievance/report/{id}', 'Admin\GrievanceController@detail_report')->name('grievance.report.detail');
        Route::post('/grievance/report/update/{id}', 'Admin\GrievanceController@update_report')->name('grievance.report.update');
        Route::post('/grievance/report/update_status', 'Admin\GrievanceController@update_status_report')->name('grievance.report.update_status');
        Route::resource('/grievance', 'Admin\GrievanceController');

        // Safeguard Incident
        Route::get('/hseincident/read', 'Admin\SafeguardIncidentController@read')->name('hseincident.read');
        Route::post('/hseincident/approved', 'Admin\SafeguardIncidentController@approved')->name('hseincident.approved');
        Route::resource('/hseincident', 'Admin\SafeguardIncidentController');

        // Route Stock Adjustment
        Route::get('/stockadjustment/read', 'Admin\StockAdjustmentController@read')->name('stockadjustment.read');
        Route::get('/stockadjustment/getitemserial', 'Admin\StockAdjustmentController@getitemserial')->name('stockadjustment.getitemserial');
        Route::get('/stockadjustment/selectproduct', 'Admin\StockAdjustmentController@selectproduct')->name('stockadjustment.selectproduct');
        Route::get('/stockadjustment/getuomproduct', 'Admin\StockAdjustmentController@getuomproduct')->name('stockadjustment.getuomproduct');
        Route::get('/stockadjustment/getdetailserial', 'Admin\StockAdjustmentController@getdetailserial')->name('stockadjustment.getdetailserial');
        Route::resource('/stockadjustment', 'Admin\StockAdjustmentController');
        // Route Warehouse
        Route::get('/warehouse/read', 'Admin\WarehouseController@read')->name('warehouse.read');
        Route::get('/warehouse/select', 'Admin\WarehouseController@select')->name('warehouse.select');
        Route::get('/warehouse/selectrack','Admin\WarehouseController@selectrack')->name('warehouse.selectrack');
        Route::get('/warehouse/selectbin','Admin\WarehouseController@selectbin')->name('warehouse.selectbin');
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
        Route::get('/product/export','Admin\ProductController@export')->name('product.export');
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
        Route::get('/businesstrip/createdeclare', 'Admin\BusinessTripController@createdeclare')->name('businesstrip.createdeclare');
        Route::resource('/businesstrip', 'Admin\BusinessTripController');
        // Route Business Trip Declaration
        Route::get('/declaration/read', 'Admin\BusinessTripDeclarationController@read')->name('declaration.read');
        Route::resource('/declaration', 'Admin\BusinessTripDeclarationController');
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
        Route::get('/employee/dig','Admin\EmployeeController@dig')->name('employee.dig');
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
        Route::get('/contractreceipt/bulkdownload', 'Admin\ContractReceiptController@bulkdownload')->name('contractreceipt.bulkdownload');
        Route::post('/contractreceipt/approval', 'Admin\ContractReceiptController@approval')->name('contractreceipt.approval');
        Route::resource('/contractreceipt', 'Admin\ContractReceiptController');

        // Product Serial
        Route::resource('/productserial', 'Admin\ProductSerialController');

        // Goods Receipt
        Route::get('/goodsreceipt/read','Admin\GoodsReceiptController@read')->name('goodsreceipt.read');
        Route::get('/goodsreceipt/contractproducts','Admin\GoodsReceiptController@contractproducts')->name('goodsreceipt.contractproducts');
        Route::get('/goodsreceipt/borrowingproducts','Admin\GoodsReceiptController@borrowingproducts')->name('goodsreceipt.borrowingproducts');
        Route::get('/goodsreceipt/transferproducts','Admin\GoodsReceiptController@transferproducts')->name('goodsreceipt.transferproducts');
        Route::get('/goodsreceipt/readserial','Admin\GoodsReceiptController@readserial')->name('goodsreceipt.readserial');        
        Route::get('/goodsreceipt/export','Admin\GoodsReceiptController@export')->name('goodsreceipt.export');
        Route::resource('/goodsreceipt', 'Admin\GoodsReceiptController');

        // Attendance
        Route::get('/attendance/generate', 'Admin\AttendanceController@generateHeaderWhenNotAttend')->name('attendance.generate');
        Route::get('/attendance/attendance/{month}/{year}', 'Admin\AttendanceController@generateAttendanceAMonth')->name('attendance.attendance');
        Route::get('/attendance/read', 'Admin\AttendanceController@read')->name('attendance.read');
        Route::get('/attendance/create/{backdate}', 'Admin\AttendanceController@create')->name('attendance.create');
        Route::resource('/attendance', 'Admin\AttendanceController')->except([
            'create'
        ]);

        // Attendance Log
        Route::get('/attendancelog/read', 'Admin\AttendanceLogController@read')->name('attendancelog.read');
        Route::resource('/attendancelog', 'Admin\AttendanceLogController');

        // Product Borrowing        
        Route::get('/productborrowing/read','Admin\ProductBorrowingController@read')->name('productborrowing.read');        
        Route::get('/productborrowing/selectwarehouse','Admin\ProductBorrowingController@selectwarehouse')->name('productborrowing.selectwarehouse');        
        Route::get('/productborrowing/selectproduct','Admin\ProductBorrowingController@selectproduct')->name('productborrowing.selectproduct');        
        Route::get('/productborrowing/readarchived','Admin\ProductBorrowingController@readarchived')->name('productborrowing.readarchived');
        Route::get('/productborrowing/readserial','Admin\ProductBorrowingController@readserial')->name('productborrowing.readserial');
        Route::get('/productborrowing/archive/{id}','Admin\ProductBorrowingController@archive');
        Route::get('/productborrowing/delete/{id}','Admin\ProductBorrowingController@destroy');                
        Route::resource('/productborrowing','Admin\ProductBorrowingController');

        // Product Consumable
        Route::get('/consumable/read', 'Admin\ProductConsumableController@read')->name('consumable.read');
        Route::get('/consumable/select', 'Admin\ProductConsumableController@select')->name('consumable.select');
        Route::resource('/consumable', 'Admin\ProductConsumableController');

        // Product Transfers        
        Route::get('/producttransfer/read','Admin\ProductTransferController@read')->name('producttransfer.read');
        Route::get('/producttransfer/readarchived','Admin\ProductTransferController@readarchived')->name('producttransfer.readarchived');
        Route::get('/producttransfer/delete/{id}','Admin\ProductTransferController@destroy');                
        Route::get('/producttransfer/archive/{id}','Admin\ProductTransferController@archive');
        Route::resource('/producttransfer','Admin\ProductTransferController');

        // COnfig
        Route::resource('/config', 'Admin\ConfigController');

        // Country
        Route::get('/country/select', 'Admin\CountryController@select')->name('country.select');
        Route::resource('/country', 'Admin\CountryController');

        // Currency
        Route::get('/currency/read', 'Admin\CurrencyController@read')->name('currency.read');
        Route::get('/currency/select', 'Admin\CurrencyController@select')->name('currency.select');
        Route::resource('/currency', 'Admin\CurrencyController');

        // Attendance Request
        Route::get('/attendancerequest/read', 'Admin\AttendanceRequestController@read')->name('attendancerequest.read');
        Route::post('/attendancerequest/approve', 'Admin\AttendanceRequestController@approve')->name('attendancerequest.approve');
        Route::resource('/attendancerequest', 'Admin\AttendanceRequestController');

        // Goods Issue 
        Route::get('/goodsissue/read','Admin\GoodsIssueController@read')->name('goodsissue.read');
        Route::get('/goodsissue/consumableproducts','Admin\GoodsIssueController@consumableproducts')->name('goodsissue.consumableproducts');
        Route::get('/goodsissue/transferproducts','Admin\GoodsIssueController@transferproducts')->name('goodsissue.transferproducts');
        Route::get('/goodsissue/borrowingprodutcs','Admin\GoodsIssueController@borrowingproducts')->name('goodsissue.borrowingproducts');
        Route::get('/goodsissue/readserial','Admin\GoodsIssueController@readserial')->name('goodsissue.readserial');
        Route::get('/goodsissue/print','Admin\GoodsIssueController@print')->name('goodsissue.print');
        Route::get('/goodsissue/export','Admin\GoodsIssueController@export')->name('goodsissue.export');
        Route::resource('/goodsissue','Admin\GoodsIssueController');

        // Stock Movement
        Route::get('/stockmovement/read','Admin\StockMovementController@read')->name('stockmovement.read');
        Route::resource('/stockmovement','Admin\StockMovementController');

        // Stock Adjustment 
        Route::get('/stockadjustment/read','Admin\StockAdjusmentController@read')->name('stockadjustment.read');
        Route::post('/stockadjustment/productserial','Admin\StockAdjusmentController@productserial')->name('stockadjustment.productserial');
        Route::resource('/stockadjustment','Admin\StockAdjusmentController');

        // Contract Document Receipt Detail
        Route::resource('/contractdocument', 'Admin\ContractDocumentReceiptDetailController');

        // Contract Document Receipt Detail
        Route::get('/salaryreport/read', 'Admin\SalaryReportController@read')->name('salaryreport.read');
        Route::resource('/salaryreport', 'Admin\SalaryReportController');

        // Salary Report Detail
        Route::get('/salarydetail/read', 'Admin\SalaryReportDetailController@read')->name('salarydetail.read');
        Route::resource('/salarydetail', 'Admin\SalaryReportDetailController');

        // Working Area
        Route::get('/area/read', 'Admin\AreaController@read')->name('area.read');
        Route::get('/area/select', 'Admin\AreaController@select')->name('area.select');
        Route::resource('/area', 'Admin\AreaController');

        // Equipment
        Route::get('/equipment/read', 'Admin\EquipmentController@read')->name('equipment.read');
        Route::get('/equipment/select', 'Admin\EquipmentController@select')->name('equipment.select');
        Route::resource('/equipment', 'Admin\EquipmentController');

        // Document Center
        Route::get('/documentcenter/read', 'Admin\DocumentCenterController@read')->name('documentcenter.read');
        Route::get('/documentcenter/select', 'Admin\DocumentCenterController@select')->name('documentcenter.select');
        Route::get('/documentcenter/data/{id}', 'Admin\DocumentCenterController@getDocumentCenterData')->name('documentcenter.data');
        Route::get('/documentcenter/{page}/', 'Admin\DocumentCenterController@index')->name('documentcenter.index');
        Route::get('/documentcenter/{page}/create/{category}', 'Admin\DocumentCenterController@create')->name('documentcenter.create');
        Route::get('/documentcenter/{page}/{id}/edit', 'Admin\DocumentCenterController@edit')->name('documentcenter.edit');
        Route::resource('/documentcenter', 'Admin\DocumentCenterController')->except([
            'index',
            'create',
            'edit'
        ]);

        // Document Type
        Route::get('/documenttype/read', 'Admin\DocumentTypeController@read')->name('documenttype.read');
        Route::get('/documenttype/select', 'Admin\DocumentTypeController@select')->name('documenttype.select');
        Route::resource('/documenttype', 'Admin\DocumentTypeController');

        // Organization Code
        Route::get('/organization/read', 'Admin\OrganizationCodeController@read')->name('organization.read');
        Route::get('/organization/select', 'Admin\OrganizationCodeController@select')->name('organization.select');
        Route::resource('/organization', 'Admin\OrganizationCodeController');

        // Unit Code
        Route::get('/unitcode/read', 'Admin\UnitCodeController@read')->name('unitcode.read');
        Route::get('/unitcode/select', 'Admin\UnitCodeController@select')->name('unitcode.select');
        Route::resource('/unitcode', 'Admin\UnitCodeController');

        // Document Center Document
        Route::get('/centerdocument/read', 'Admin\DocumentCenterDocumentController@read')->name('centerdocument.read');
        Route::get('/emaildata/{id}', 'Admin\DocumentCenterDocumentController@emailData')->name('emaildata');
        Route::resource('/centerdocument', 'Admin\DocumentCenterDocumentController');

        // Document Center Document Detail
        Route::resource('/documentdetail', 'Admin\DocumentCenterDocumentDetailController');

        // Document Center Log
        Route::get('/documentlog/read', 'Admin\DocumentCenterLogController@read')->name('documentlog.read');
        Route::resource('/documentlog', 'Admin\DocumentCenterLogController');

        // Reimbursement
        Route::get('/reimbursement/read', 'Admin\ReimbursementController@read')->name('reimbursement.read');
        Route::get('/reimbursement/select', 'Admin\ReimbursementController@select')->name('reimbursement.select');
        Route::resource('/reimbursement', 'Admin\ReimbursementController');

        // Document External Properties
        Route::resource('/docexternalproperties', 'Admin\ExternalPropertiesController');
        Route::resource('/doccenterproperties', 'Admin\DocCenterPropertiesController');

        // Document External Site Code
        Route::get('/docexternalproperties/sitecode/read', 'Admin\ExternalProperties\SiteCodeController@read')->name('sitecode.read');
        Route::get('/docexternalproperties/sitecode/select', 'Admin\ExternalProperties\SiteCodeController@select')->name('sitecode.select');
        Route::resource('/docexternalproperties/sitecode', 'Admin\ExternalProperties\SiteCodeController');

        // Document External Discipline Code
        Route::get('/docexternalproperties/disciplinecode/read', 'Admin\ExternalProperties\DisciplineCodeController@read')->name('disciplinecode.read');
        Route::get('/docexternalproperties/disciplinecode/select', 'Admin\ExternalProperties\DisciplineCodeController@select')->name('disciplinecode.select');
        Route::resource('/docexternalproperties/disciplinecode', 'Admin\ExternalProperties\DisciplineCodeController');

        // Document External Document Type
        Route::get('/docexternalproperties/documenttypeext/read', 'Admin\ExternalProperties\DocumentTypeController@read')->name('documenttypeext.read');
        Route::get('/docexternalproperties/documenttypeext/select', 'Admin\ExternalProperties\DocumentTypeController@select')->name('documenttypeext.select');
        Route::resource('/docexternalproperties/documenttypeext', 'Admin\ExternalProperties\DocumentTypeController');

        // Document External Originator Code
        Route::get('/docexternalproperties/originatorcode/read', 'Admin\ExternalProperties\OriginatorCodeController@read')->name('originatorcode.read');
        Route::get('/docexternalproperties/originatorcode/select', 'Admin\ExternalProperties\OriginatorCodeController@select')->name('originatorcode.select');
        Route::resource('/docexternalproperties/originatorcode', 'Admin\ExternalProperties\OriginatorCodeController');

        // Document External Phase Code
        Route::get('/docexternalproperties/phasecode/read', 'Admin\ExternalProperties\PhaseCodeController@read')->name('phasecode.read');
        Route::get('/docexternalproperties/phasecode/select', 'Admin\ExternalProperties\PhaseCodeController@select')->name('phasecode.select');
        Route::resource('/docexternalproperties/phasecode', 'Admin\ExternalProperties\PhaseCodeController');

        // Document External Sheet Size
        Route::get('/docexternalproperties/sheetsize/read', 'Admin\ExternalProperties\SheetSizeController@read')->name('sheetsize.read');
        Route::get('/docexternalproperties/sheetsize/select', 'Admin\ExternalProperties\SheetSizeController@select')->name('sheetsize.select');
        Route::resource('/docexternalproperties/sheetsize', 'Admin\ExternalProperties\SheetSizeController');

        // Document External KKS Category
        Route::get('/docexternalproperties/kkscategory/read', 'Admin\ExternalProperties\KksCategoryController@read')->name('kkscategory.read');
        Route::get('/docexternalproperties/kkscategory/select', 'Admin\ExternalProperties\KksCategoryController@select')->name('kkscategory.select');
        Route::resource('/docexternalproperties/kkscategory', 'Admin\ExternalProperties\KksCategoryController');

        // Document External KKS Code
        Route::get('/docexternalproperties/kkscode/read', 'Admin\ExternalProperties\KksCodeController@read')->name('kkscode.read');
        Route::get('/docexternalproperties/kkscode/select', 'Admin\ExternalProperties\KksCodeController@select')->name('kkscode.select');
        Route::resource('/docexternalproperties/kkscode', 'Admin\ExternalProperties\KksCodeController');

        // Document External Contractor Name
        Route::get('/docexternalproperties/contractorname/read', 'Admin\ExternalProperties\ContractorNameController@read')->name('contractorname.read');
        Route::get('/docexternalproperties/contractorname/select', 'Admin\ExternalProperties\ContractorNameController@select')->name('contractorname.select');
        Route::resource('/docexternalproperties/contractorname', 'Admin\ExternalProperties\ContractorNameController');

        // Document External Document Category
        Route::get('/documentcategoriesexternal/read', 'Admin\DocumentExternal\CategoryDocumentExternalController@read')->name('documentcategoriesexternal.read');
        Route::resource('/documentcategoriesexternal', 'Admin\DocumentExternal\CategoryDocumentExternalController');

        // Document External
        Route::get('/documentcenterexternal/read', 'Admin\DocumentExternal\DocumentExternalController@read')->name('documentcenterexternal.read');
        Route::get('/documentcenterexternal/{page}/{id}/readmatrix', 'Admin\DocumentExternal\DocumentExternalController@readMatrix')->name('documentcenterexternal.readmatrix');
        Route::get('/documentcenterexternal/select', 'Admin\DocumentExternal\DocumentExternalController@select')->name('documentcenterexternal.select');
        Route::put('/documentcenterexternal/updatematrix/{id}', 'Admin\DocumentExternal\DocumentExternalController@updateMatrix')->name('documentcenterexternal.updatematrix');
        Route::post('/documentcenterexternal/{page}/{id}', 'Admin\DocumentExternal\DocumentExternalController@destroy')->name('documentcenterexternal.destroy');
        Route::get('/documentcenterexternal/{page}/', 'Admin\DocumentExternal\DocumentExternalController@index')->name('documentcenterexternal.index');
        Route::get('/documentcenterexternal/{page}/create', 'Admin\DocumentExternal\DocumentExternalController@create')->name('documentcenterexternal.create');
        Route::get('/documentcenterexternal/{page}/{id}/edit', 'Admin\DocumentExternal\DocumentExternalController@edit')->name('documentcenterexternal.edit');
        Route::resource('/documentcenterexternal', 'Admin\DocumentExternal\DocumentExternalController')->except([
            'index',
            'create',
            'edit'
        ]);

        // Document External Revision
        Route::get('/revision/read', 'Admin\DocumentExternal\RevisionController@read')->name('revision.read');
        Route::get('/revision/latestno', 'Admin\DocumentExternal\RevisionController@getLatestRevisionNo')->name('revision.latestno');
        Route::get('/revision/latest/{id}', 'Admin\DocumentExternal\RevisionController@getLatestRevision')->name('revision.latest');
        Route::post('/revision/storelog', 'Admin\DocumentExternal\RevisionController@storeLog')->name('revision.storelog');
        Route::delete('/revision/destroyfile/{id}', 'Admin\DocumentExternal\RevisionController@destroyFile')->name('revision.destroyfile');
        Route::delete('/revision/destroy/{id}', 'Admin\DocumentExternal\RevisionController@destroy')->name('revision.destroy');
        Route::resource('/revision', 'Admin\DocumentExternal\RevisionController');

        // Workflow
        Route::get('/workflow/read', 'Admin\DocumentExternal\Workflow\WorkflowController@read')->name('workflow.read');
        Route::get('/workflow/{id}', 'Admin\DocumentExternal\Workflow\WorkflowController@index')->name('workflow.workflow');
        Route::resource('/workflow', 'Admin\DocumentExternal\Workflow\WorkflowController');

        // Group Workflow
        Route::resource('/groupworkflow', 'Admin\DocumentExternal\Workflow\GroupWorkflowController');

        // Transmittal Menu
        Route::group(['prefix' => 'transmittalproperties', 'as' => 'transmittalproperties.', 'namespace' => 'Admin\Transmittal\TransmittalProperties'], function() {
            Route::resource('/', 'TransmittalPropertiesController');
            Route::get('readcontractor', 'CategoryContractorController@read')->name('readcontractor');
            Route::get('readcode', 'OrganizationCodeController@read')->name('readcode');
            Route::get('getall', function () {
                return new OrganizationCodeCollection(TransmittalOrganizationCode::orderBy('code')->get());
            })->name('getall');
            Route::resource('categorycontractor', 'CategoryContractorController');
            Route::resource('organizationcode', 'OrganizationCodeController');
        });

        // Incoming Route
        Route::resource('/incoming', 'Admin\Transmittal\IncomingController');

        // Outcoming Route
        Route::group(['prefix' => 'outcoming', 'as' => 'outcoming.', 'namespace' => 'Admin\Transmittal'], function() {
            Route::get('/{code}/create', 'OutcomingController@create')->name('create');
            Route::get('/{code}/{id}/edit', 'OutcomingController@edit')->name('edit');
            Route::post('/defaultdata', 'OutcomingController@defaultData')->name('defaultdata');
            Route::get('/cc/select', 'OutcomingController@selectCC')->name('selectcc');
            Route::get('/attention/select', 'OutcomingController@selectAttention')->name('selectattention');
            Route::get('/contractor/select', 'OutcomingController@selectContractor')->name('selectcontractor');
            Route::get('/read', 'OutcomingController@read')->name('read');
            Route::get('/revision/select', 'OutcomingController@selectRevision')->name('selectrevision');
            Route::delete('/destroydocument', 'OutcomingController@destroyDocument')->name('destroydocument');
            Route::get('/generatepdf/{id}', 'OutcomingController@pdfview')->name('generatepdf');
            Route::resource('/', 'OutcomingController')->except([
                'create',
                'edit',
            ]);
        });
    });
});