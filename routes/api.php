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
            Route::get('members/{id}/detail', 'MemberAPIController@detail');
            Route::resource('members', 'MemberAPIController');
    
            Route::get('/earnChopRules', 'EarnChopRuleAPIController@index');
            Route::get('/consumeChopRules', 'ConsumeChopRuleAPIController@index');
            Route::post('/chops/add', 'ChopAPIController@manualAddChops');
            Route::post('/chops/consume', 'ChopAPIController@consumeChops');
            Route::post('/chops/consume/{id}/void', 'ChopAPIController@voidConsumeChops');
            Route::resource('roles', 'RoleAPIController');
    
            Route::resource('branches', 'BranchAPIController');
    
            Route::resource('ranks', 'RankAPIController');
    
            Route::resource('consumeChopRules', 'ConsumeChopRuleAPIController');
            
            Route::resource('earnChopRules', 'EarnChopRuleAPIController');
    
            Route::resource('chopRecords', 'ChopRecordAPIController');
            
            Route::resource('transactions', 'TransactionAPIController');
    
            Route::resource('chopExpiredSettings', 'ChopExpiredSettingAPIController');
    
            Route::resource('chops', 'ChopAPIController');
    
            Route::resource('users', 'UserAPIController');
        });
    });
});
