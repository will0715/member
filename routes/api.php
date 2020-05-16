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


// for customer login
Route::post('/auth/login', 'UserAPIController@login');

Route::group(['prefix' => 'internal'], function () {
    Route::middleware(['internal.customer.switch', 'auth:api'])->group(function () {

    });
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('/auth/login', 'UserAPIController@login');
    Route::resource('customers', 'CustomerAPIController');
    
    Route::group(['middleware' => 'auth:api'], function(){
        
        Route::middleware(['customer.switch'])->group(function () {
            Route::get('/report/dashboard', 'ReportAPIController@dashboard');

            Route::get('/user/me', 'UserAPIController@me');
            
            Route::post('members/query', 'MemberAPIController@queryByPhone');
            Route::get('members/{phone}/chops', 'MemberAPIController@getChops');
            Route::get('members/{phone}/chopsDetail', 'MemberAPIController@getChopsDetail');
            Route::get('members/{phone}/orderRecords', 'MemberAPIController@getOrderRecords');
            Route::get('members/{phone}/prepaidcard', 'MemberAPIController@getBalance');
            Route::get('members/{phone}/information', 'MemberAPIController@information');
            Route::get('members/{id}/detail', 'MemberAPIController@detail');
            Route::resource('members', 'MemberAPIController');
    
            Route::get('/earnChopRules', 'EarnChopRuleAPIController@index');
            Route::get('/consumeChopRules', 'ConsumeChopRuleAPIController@index');

            Route::post('/chops/add', 'ChopAPIController@manualAddChops');
            Route::post('/chops/consume', 'ChopAPIController@consumeChops');
            Route::post('/chops/consume/{id}/void', 'ChopAPIController@voidConsumeChops');
            Route::delete('/chops/consume/{id}', 'ChopAPIController@voidConsumeChops');

            Route::group(['prefix' => 'prepaidcards'], function () {
                Route::post('/topup', 'PrepaidCardAPIController@topup');
                Route::post('/payment', 'PrepaidCardAPIController@payment');
                Route::post('/payment/{id}/void', 'PrepaidCardAPIController@voidPayment');
                Route::delete('/payment/{id}', 'PrepaidCardAPIController@voidPayment');
            });

            Route::resource('roles', 'RoleAPIController');
    
            Route::resource('branches', 'BranchAPIController');
    
            Route::resource('ranks', 'RankAPIController');
    
            Route::resource('consumeChopRules', 'ConsumeChopRuleAPIController');
            
            Route::resource('earnChopRules', 'EarnChopRuleAPIController');
            
            Route::resource('transactions', 'TransactionAPIController');
            Route::post('/transactions/{id}/void', 'TransactionAPIController@destroy');
    
            Route::resource('chopExpiredSettings', 'ChopExpiredSettingAPIController');
    
            Route::resource('chops', 'ChopAPIController');
    
            Route::resource('users', 'UserAPIController');
        });
    });
});
