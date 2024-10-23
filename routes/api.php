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

Route::name('api.')->group(function () {
    Route::prefix('v1')->name('v1.')->group(function () {
        Route::prefix('coin/{coin}')->name('coin.')->group(function () {
            Route::prefix('price')->name('price.')->group(function () {
                Route::get('', 'Api\v1\Coin\PriceController@show')->name('show');
            });
        });
    });
});