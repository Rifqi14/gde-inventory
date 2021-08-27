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

// Account and Auth
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
            // Logout 
            Route::post('/signout', 'API\Auth\LoginController@logout');
        });        
    });

    // Role
    Route::post('/role','API\RoleController@read');    

    // Product
    Route::group([
        'middleware' => ['auth:api'],
        'prefix' => 'product'
    ], function(){
        Route::post('/list', 'API\ProductController@read');
    });
});
