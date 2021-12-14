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

Route::namespace('App\Http\Controllers')->group(function() {
    Route::post('login', 'AuthController@login');
    Route::get('profile', 'AuthController@getProfile');

    Route::group(['prefix' => 'loan'], function () {
        Route::get('/', 'LoanController@index');
        Route::post('/', 'LoanController@store');
        Route::put('{id}', 'LoanController@update');
    });

    Route::group(['prefix' => 'payment'], function () {
        Route::post('/', 'PaymentController@store');
    });
});


