<?php

use Illuminate\Http\Request;

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



Route::group([], function () {

    // public routes
    Route::post('/login', 'Api\AuthController@login')->name('api.login');
    Route::post('/login/refresh', 'Api\AuthController@loginRefresh')->name('api.login.refresh');
    Route::post('/register', 'Api\AuthController@register')->name('api.register');
    Route::post('/refresh', 'Api\AuthController@refreshToken')->name('api.refresh');
    Route::post('/logout', 'Api\AuthController@logout')->name('api.logout');
    Route::group(['/prefix' => 'password'], function () {

        Route::post('/reset', 'Api\AuthController@resetPassword')->name('api.password.reset');
        Route::post('/send', 'Api\AuthController@sendPassword')->name('api.password.send');
    });

    // private routes
    Route::middleware('auth:api')->group(function () {

        Route::get('/user', 'Api\AuthController@getUserInfo')->name('api.user');
        Route::get('/users', function () {
            return factory('App\Models\Auth\User', 10)->make();
        })->name('api.users');
    });
});


