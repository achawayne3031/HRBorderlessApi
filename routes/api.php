<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// Route::group([
//     'as' => 'company.',
//     'namespace' => 'App\Http\Controllers\Company',
//     'middleware' => ['cors']
// ], function () {
//     Route::post('/login', 'AuthController@login');
//     Route::post('/register', 'AuthController@register');
// });



Route::group([
    'prefix' => 'company',
    'namespace' => 'App\Http\Controllers\Company',
    'middleware' => ['cors']
], function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('/login', 'AuthController@login');
        Route::post('/register', 'AuthController@register');
    });

    Route::group([
        'prefix' => 'jobs',
        'middleware' => ['company', 'auth:company']

    ], function () {

        Route::post('/', 'CompanyJobsController@create');
        Route::put('/{id}', 'CompanyJobsController@update');
        Route::delete('/{id}', 'CompanyJobsController@delete');
    });
});






Route::group([
    'prefix' => 'candidate',
    'namespace' => 'App\Http\Controllers\Candidate',
    'middleware' => ['cors']
], function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('/login', 'AuthController@login');
        Route::post('/register', 'AuthController@register');
    });


    // Route::post('/login', 'AuthController@login');
    // Route::post('/register', 'AuthController@register');
});




Route::group([
    'namespace' => 'App\Http\Controllers',
    'middleware' => ['cors']
], function () {
    Route::get('/jobs', 'JobsController@jobs');
});
