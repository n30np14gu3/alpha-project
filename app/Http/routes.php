<?php

Route::group(['domain' => 'api.'.env('APP_DOMAIN')], function () {
    Route::get('{category}/{method}', function () {
        dd("API IN DEV");
    });
});

Route::any('/', ['uses' => 'lendingController@index', 'as' => 'index', 'middleware' => 'action']);
Route::any('invite/{refId}', ['uses' => 'lendingController@referer', 'as' => 'referer']);

Route::get('lang/{lang}',  ['uses' => 'lendingController@stub', 'middleware' => 'langChanger']);

Route::get('dashboard', ['uses' => 'dashboardController@index', 'as' => 'dashboard', 'middleware' => 'action']);


Route::group(['prefix' => 'action', 'middleware' => 'action'], function (){
   Route::post('verify_steam', ['before' => 'csrf', 'uses' => 'actionController@verifyAccount', 'as' => 'steam']);
   Route::get('logout', ['uses' => 'actionController@logout', 'as' => 'logout']);
   Route::post('auth', ['uses' => 'actionController@login', 'as' => 'login']);
});
