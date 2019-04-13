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
    Route::post('register', ['uses' => 'actionController@register', 'as' => 'register']);
    Route::post('login', ['uses' => 'actionController@login', 'as' => 'login']);
    Route::get('logout', ['uses' => 'actionController@logout', 'as' => 'logout']);
});

Route::group(['prefix' => 'email'], function (){
   Route::get('confirm/{confirm_code}', ['uses' => 'mailController@confirm', 'as' => 'confirm']);
});

Route::group(['prefix' => 'mail_test'], function (){
    Route::get('registration', function (){return view('mail.types.reg_complete' ,['link' => '']);});
});