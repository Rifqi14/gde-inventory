<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth',
// ], function() {
//     Route::post('/login', 'Auth\API\LoginController@login');
//     Route::post('/logout', 'Auth\API\LoginController@logout');
//     Route::post('/refresh', 'Auth\API\LoginController@refresh');
//     Route::post('/profile', 'Auth\API\LoginController@profile');    
    
// });

Route::group([
    'middleware' => 'api'
],function(){
    // Account and Auth
    Route::group([
        'prefix' => 'account'
    ], function(){
        // Login
        Route::post('/signin', 'API\Auth\LoginController@login');
        // Register
        Route::post('/signup', 'API\Auth\RegisterController@signup');

        Route::group(['middleware' => ['auth:api']], function(){       
            // Profile
            Route::post('/me','API\Auth\ProfileController');
            // Logout 
            Route::post('/signout', 'API\Auth\LoginController@logout');
        });        
    });

    // Role
    Route::post('/role','API\RoleController@read');        

    // Dashboard
    Route::group([
        'middleware' => ['auth:api'],
        'prefix'     => 'dashboard'
    ], function(){
        // Activity History
        Route::get('/activityhistory', 'API\StockMovementController@read');
    }); 

    // Product
    Route::group([
        'middleware' => ['auth:api'],
        'prefix'     => 'product'
    ], function(){
        // List 
        Route::get('/list', 'API\ProductController@read');
        // Latest Product 
        Route::get('/latest','API\ProductController@latest');
        // Detail
        Route::post('/detail','API\ProductController@show');
        // History
        Route::get('/history', 'API\StockMovementController@read');
        Route::post('/totalhistory', 'API\StockMovementController@total');
    });

    // Product Borrowing
    Route::group([
        'middleware' => ['auth:api'],
        'prefix'     => 'productborrowing'
    ], function(){
        // List
        Route::get('/list', 'API\ProductBorrowingController@read');
        // Edit Product Borrowing
        Route::get('/edit/{id}', 'API\ProductBorrowingController@edit')->name('productborrowing.edit');                
        Route::resource('/action','API\ProductBorrowingController');
    });
});
