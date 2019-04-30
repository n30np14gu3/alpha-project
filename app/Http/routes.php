<?php

Route::group(['domain' => 'api.'.env('APP_DOMAIN')], function () {
    Route::get('{category}/{method}', function () {
        dd("API IN DEV");
    });
});

Route::any('/', ['uses' => 'lendingController@index', 'as' => 'index']);
Route::any('/invite/{refId}', ['uses' => 'lendingController@referer', 'as' => 'referer']);

Route::get('/lang/{lang}',  ['uses' => 'lendingController@changeLang']);

Route::get('/dashboard', ['uses' => 'dashboardController@index', 'as' => 'dashboard', 'middleware' => 'action']);

Route::group(['prefix' => 'form', 'middleware' => 'form'], function (){
    Route::get('login', ['uses' => 'formController@login', 'as' => 'show_login']);
    Route::get('register', ['uses' => 'formController@register', 'as' => 'show_register']);
    Route::get('reset_password', ['uses' => 'formController@resetPassword', 'as' => 'show_reset_password']);
});

Route::get('/logout', ['uses' => 'actionController@logout', 'as' => 'logout']);
Route::post('/register', ['uses' => 'actionController@register', 'as' => 'register']);
Route::post('/fast_register', ['uses' => 'actionController@fastRegister', 'as' => 'fast_register']);
Route::post('/login', ['uses' => 'actionController@login', 'as' => 'login']);
Route::post('/reset_password', ['uses' => 'actionController@resetPassword', 'as' => 'reset_password']);

Route::group(['prefix' => 'action', 'middleware' => 'action'], function (){
    Route::post('verify_steam', ['uses' => 'actionController@verifySteam', 'as' => 'verify_steam']);
    Route::post('save_info', ['uses' => 'actionController@saveInfo', 'as' => 'save_info']);
    Route::post('password_change', ['uses' => 'actionController@changePassword', 'as' => 'password_change']);
});

Route::group(['prefix' => 'email'], function (){
   Route::get('confirm/{confirm_code}', ['uses' => 'mailController@confirm', 'as' => 'confirm']);
   Route::get('reset_password/{reset_code}', ['uses' => 'mailController@resetPassword', 'as' => 'reset_password']);
});

Route::get('/payment', ['uses' => 'paymentController@prepare', 'as' => 'payment_prepare',  'middleware' => 'action']);

Route::group(['prefix' => 'transfer', 'middleware' => 'action'], function (){
   Route::post('callback', ['uses' => 'paymentController@callback', 'as' => 'payment_callback']);
   Route::get('success', ['uses' => 'paymentController@success', 'as' => 'payment_success']);
   Route::get('error', ['uses' => 'paymentController@error', 'as' => 'payment_error']);
});
Route::group(['prefix' => 'mail_test'], function (){
    Route::get('reg_complete', function (){return view('mail.types.reg_complete' ,['link' => '' , 'mail_title' => '1337']);});
    Route::get('new_password', function (){return view('mail.types.new_password' ,['link' => '' , 'mail_title' => '1337']);});
    Route::get('password_reset', function (){return view('mail.types.password_reset' ,['link' => '' , 'mail_title' => '1337']);});
});
