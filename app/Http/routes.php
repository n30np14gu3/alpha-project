<?php
/**
 * FULL ROUTES
 */

//INDEX ROUTE
Route::any('/', ['uses' => 'lendingController@index', 'as' => 'index']);

//INVITE USER ROUTE
Route::any('/invite/{refId}', ['uses' => 'lendingController@referer', 'as' => 'referer']);

//CHANGE LANG ROUTE
Route::get('/lang/{lang}',  ['uses' => 'lendingController@changeLang']);

//
Route::get('/dashboard', ['uses' => 'dashboardController@index', 'as' => 'dashboard', 'middleware' => 'action']);
Route::get('/download/{game_id}', ['uses' => 'dashboardController@downloadLoader', 'as' => 'download_loader', 'middleware' => 'action'])->where(['game_id' => '[0-9+]']);

Route::get('/legal', ['uses' => 'lendingController@legal', 'as' => 'legal']);

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
    Route::post('purchase', ['uses' => 'actionController@purchase', 'as' => 'purchase']);

    Route::group(['prefix' => 'admin', 'middleware' => 'admin_action'], function (){
       Route::post('create_module', ['uses' => 'adminActionController@createModule', 'as' => 'module_create']);
       Route::post('create_game', ['uses' => 'adminActionController@createGame', 'as' => 'create_game']);

       Route::post('get_game_data', ['uses' => 'adminActionController@getGameData', 'as' => 'get_game_data']);

       Route::post('update_game', ['uses' => 'adminActionController@updateGame', 'as' => 'update_game']);

       Route::post('create_country', ['uses' => 'adminActionController@createCountry', 'as' => 'create_country']);


       Route::post('create_increment', ['uses' => 'adminActionController@createIncrement', 'as' => 'create_increment']);
       Route::post('create_cost', ['uses' => 'adminActionController@createCost', 'as' => 'create_cost']);
       Route::post('create_product_feature', ['uses' => 'adminActionController@createProductFeature', 'as' => 'create_product_feature']);
       Route::post('create_product', ['uses' => 'adminActionController@createProduct', 'as' => 'create_product']);
    });
});

Route::group(['prefix' => 'email'], function (){
   Route::get('confirm/{confirm_code}', ['uses' => 'mailController@confirm', 'as' => 'confirm']);
   Route::get('reset_password/{reset_code}', ['uses' => 'mailController@resetPassword', 'as' => 'reset_password']);
});

Route::post('/payment', ['uses' => 'paymentController@prepare', 'as' => 'payment_prepare',  'middleware' => 'action']);

Route::group(['prefix' => 'transfer'], function (){
   Route::post('callback', ['uses' => 'paymentController@callback', 'as' => 'payment_callback']);
   Route::post('success', ['uses' => 'paymentController@success', 'as' => 'payment_success', 'middleware' => 'action']);
   Route::post('fail', ['uses' => 'paymentController@fail', 'as' => 'payment_fail', 'middleware' => 'action']);
});

Route::group(['prefix' => 'support', 'middleware' => 'support'], function (){

    Route::get('/', ['uses' => 'supportController@allTickets', 'as' => 'support']);
    Route::post('create_ticket', ['uses' => 'supportController@createTicket', 'as' => 'create_ticket']);

    Route::group(['prefix' => 'ticket'], function (){
       Route::get('{ticket_id}', ['uses' => 'supportController@showTicket', 'as' => 'show_ticket'])->where(['ticket_id' => '[0-9]+']);
       Route::post('append', ['uses' => 'supportController@appendTicket', 'as' => 'append_ticket']);
       Route::post('close', ['uses' => 'supportController@closeTicket', 'as' => 'close_ticket']);
    });

    Route::group(['prefix' => 'admin', 'middleware' => 'admin_action'], function (){
        Route::post('accept', ['uses' => 'supportController@acceptTicket', 'as' => 'accept_ticket']);
        Route::group(['prefix' => 'ticket'], function (){
            Route::get('{ticket_id}', ['uses' => 'supportController@showTicketAdmin', 'as' => 'show_ticket_admin'])->where(['ticket_id' => '[0-9]+']);
            Route::post('append', ['uses' => 'supportController@appendTicketAdmin', 'as' => 'append_ticket_admin']);
            Route::post('close', ['uses' => 'supportController@closeTicketAdmin', 'as' => 'close_ticket_admin']);
        });
    });
});

Route::group(['prefix' => 'api'], function (){
    Route::post('login', ['uses' => 'apiController@login', 'as' => 'api_login']);
    //Route::get('request_session', ['uses' => 'apiController@requestSession', 'as' => 'api_request_session']);
});
