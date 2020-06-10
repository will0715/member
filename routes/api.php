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
            Route::group(['prefix' => 'report'], function () {
                Route::get('/dashboard', 'ReportAPIController@dashboard');
                Route::get('/prepaidcards/topup', 'ReportAPIController@getPrepaidcardTopupRecords');
                Route::get('/prepaidcards/payment', 'ReportAPIController@getPrepaidcardPaymentRecords');
                Route::get('/chops/add', 'ReportAPIController@getAddChopsRecords');
                Route::get('/chops/consume', 'ReportAPIController@getConsumeChopsRecords');
                Route::get('/transactions', 'ReportAPIController@getTransactionRecords');
                Route::get('/memberRegisterBranch/detail', 'ReportAPIController@getMemberRegisterBranchDetail');
                Route::get('/memberRegisterBranch/statistics', 'ReportAPIController@getMemberRegisterBranchStatistics');
            });

            Route::get('/user/me', 'UserAPIController@me');
            
            Route::group(['prefix' => 'members'], function () {
                Route::post('/query', 'MemberAPIController@queryByPhone');
                Route::get('/{phone}/chops', 'MemberAPIController@getChops');
                Route::get('/{phone}/chopsDetail', 'MemberAPIController@getChopsDetail');
                Route::get('/{phone}/chopsRecords', 'MemberAPIController@getChopsRecords');
                Route::get('/{phone}/orderRecords', 'MemberAPIController@getOrderRecords');
                Route::get('/{phone}/balance', 'MemberAPIController@getBalance');
                Route::get('/{phone}/prepaidcard', 'MemberAPIController@getPrepaidcardRecords');
                Route::get('/{phone}/information', 'MemberAPIController@information');
                Route::get('/{id}/detail', 'MemberAPIController@detail');
            });
            Route::resource('members', 'MemberAPIController');
            Route::patch('/members/{phone}/byPhone', 'MemberAPIController@updateByPhone');
            Route::delete('/members/{phone}/force', 'MemberAPIController@forceDelete');
    
            Route::get('/earnChopRules', 'EarnChopRuleAPIController@index');
            Route::get('/consumeChopRules', 'ConsumeChopRuleAPIController@index');

            Route::post('/chops/add', 'ChopAPIController@manualAddChops');
            Route::post('/chops/earn', 'ChopAPIController@earnChops');
            Route::post('/chops/earn/{id}/void', 'ChopAPIController@voidEarnChops');
            Route::post('/chops/consume', 'ChopAPIController@consumeChops');
            Route::post('/chops/consume/{id}/void', 'ChopAPIController@voidConsumeChops');
            Route::delete('/chops/consume/{id}', 'ChopAPIController@voidConsumeChops');

            Route::group(['prefix' => 'prepaidcards'], function () {
                Route::get('/', 'PrepaidCardAPIController@index');
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
            
            Route::post('/transactions/withoutEarnChops', 'TransactionAPIController@newTransactionWithoutEarnChops');
            Route::resource('transactions', 'TransactionAPIController');
            Route::post('/transactions/{id}/void', 'TransactionAPIController@destroy');
    
            Route::resource('chopExpiredSettings', 'ChopExpiredSettingAPIController');
    
            Route::resource('chops', 'ChopAPIController');
    
            Route::resource('users', 'UserAPIController');
        });
    });
});
