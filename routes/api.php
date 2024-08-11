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

Route::namespace('Client')->prefix('client')->group(function () {
    Route::middleware(['customer.switch'])->group(function () {
        Route::post('/auth/login/socialite/{socialiteProvider}', 'MemberAPIController@socialiteLogin');
        Route::post('/auth/login', 'MemberAPIController@login');
        Route::post('/auth/forgetPassword', 'ForgotPasswordController@sendResetSMS');
        Route::post('/auth/resetPassword', 'ForgotPasswordController@resetPassword');
        Route::post('members', 'MemberAPIController@store');
        Route::get('/socialProvider/line/liff', 'ConfigAPIController@lineLiff');

        Route::middleware(['auth.member'])->group(function () {
            Route::get('/information', 'MemberAPIController@information');
            Route::get('/chopsDetail', 'MemberAPIController@getChopsDetail');
            Route::get('/chopsRecords', 'MemberAPIController@getChopsRecords');
            Route::get('/orderRecords', 'MemberAPIController@getOrderRecords');
            Route::get('/prepaidcard', 'MemberAPIController@getPrepaidcardRecords');
            Route::patch('/information', 'MemberAPIController@update');
        });
    });
});

Route::group(['prefix' => 'v2', 'namespace' => 'v2'], function () {
    Route::group(['middleware' => 'auth:api'], function(){

        Route::middleware(['customer.switch'])->group(function () {
            Route::middleware(['can:view-chops'])->group(function () {
                Route::prefix('chops')->group(function () {
                    Route::post('/consume', 'ChopAPIController@consumeChops');
                    Route::post('/consume/{id}/void', 'ChopAPIController@voidConsumeChops');
                    Route::delete('/consume/{id}', 'ChopAPIController@voidConsumeChops');
                });
            });
        });

    });
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('/auth/login', 'UserAPIController@login');

    Route::group(['middleware' => 'auth:api'], function(){

        Route::middleware(['customer.switch'])->group(function () {
            Route::middleware(['can:super-admin'])->group(function () {
                Route::get('customers/{id}/adminPermission', 'CustomerAPIController@getAdminRolePermission');
                Route::patch('customers/{id}/adminPermission', 'CustomerAPIController@setAdminRolePermission');
                Route::resource('customers', 'CustomerAPIController');
            });

            Route::group(['prefix' => 'report'], function () {
                Route::get('/dashboard', 'ReportAPIController@dashboard');
                Route::get('/dashboard/today', 'ReportAPIController@todayDashboard');
                Route::get('/rankMemberSummary', 'ReportAPIController@rankMemberSummary');
                Route::get('/memberGenderTransactionAmountPercentageSummary', 'ReportAPIController@memberGenderTransactionAmountPercentageSummary');
                Route::get('/branchChopConsumeChopSummary', 'ReportAPIController@branchChopConsumeChopSummary');
                Route::get('/branchRegisterMemberSummary', 'ReportAPIController@branchRegisterMemberSummary');
                Route::get('/memberCount/groupByDate', 'ReportAPIController@getMemberCountByDate');
                Route::get('/branchCount/groupByDate', 'ReportAPIController@getBranchCountByDate');
                Route::get('/earnChops/groupByDate', 'ReportAPIController@getEarnChopsByDate');
                Route::get('/consumeChops/groupByDate', 'ReportAPIController@getConsumeChopsByDate');
                Route::get('/transactions/groupByDate', 'ReportAPIController@getTransactionCountByDate');
                Route::get('/transactionsAmount/groupByDate', 'ReportAPIController@getTransactionAmountByDate');
                Route::get('/topup/groupByDate', 'ReportAPIController@getPrepaidCardTopupByDate');
                Route::get('/payment/groupByDate', 'ReportAPIController@getPrepaidCardPaymentByDate');

                Route::middleware(['can:view-report'])->group(function () {
                    Route::get('/prepaidcards/topup', 'ReportAPIController@getPrepaidcardTopupRecords');
                    Route::get('/prepaidcards/payment', 'ReportAPIController@getPrepaidcardPaymentRecords');
                    Route::get('/chops/add', 'ReportAPIController@getAddChopsRecords');
                    Route::get('/chops/consume', 'ReportAPIController@getConsumeChopsRecords');
                    Route::get('/transactions', 'ReportAPIController@getTransactionRecords');
                    Route::get('/memberRegisterBranch/detail', 'ReportAPIController@getMemberRegisterBranchDetail');
                    Route::get('/memberRegisterBranch/statistics', 'ReportAPIController@getMemberRegisterBranchStatistics');

                    Route::get('/prepaidcards/topup/download', 'ReportAPIController@downloadPrepaidcardTopupRecords');
                    Route::get('/prepaidcards/payment/download', 'ReportAPIController@downloadPrepaidcardPaymentRecords');
                    Route::get('/chops/add/download', 'ReportAPIController@downloadAddChopsRecords');
                    Route::get('/chops/consume/download', 'ReportAPIController@downloadConsumeChopsRecords');
                    Route::get('/transactions/download', 'ReportAPIController@downloadTransactionRecords');
                    Route::get('/memberRegisterBranch/detail/download', 'ReportAPIController@downloadMemberRegisterBranchDetail');
                    Route::get('/memberRegisterBranch/statistics/download', 'ReportAPIController@downloadMemberRegisterBranchStatistics');
                });

            });

            Route::get('/user/me', 'UserAPIController@me');

            Route::middleware(['can:view-member'])->group(function () {
                Route::resource('/members', 'MemberAPIController');
                Route::prefix('members')->group(function () {
                    Route::post('/query', 'MemberAPIController@queryByPhone');
                    Route::get('/{phone}/chops', 'MemberAPIController@getChops');
                    Route::get('/{phone}/chopsDetail', 'MemberAPIController@getChopsDetail');
                    Route::get('/{phone}/chopsRecords', 'MemberAPIController@getChopsRecords');
                    Route::get('/{phone}/orderRecords', 'MemberAPIController@getOrderRecords');
                    Route::get('/{phone}/balance', 'MemberAPIController@getBalance');
                    Route::get('/{phone}/prepaidcard', 'MemberAPIController@getPrepaidcardRecords');
                    Route::get('/{phone}/information', 'MemberAPIController@information');
                    Route::get('/{id}/detail', 'MemberAPIController@detail');
                    Route::patch('/{phone}/byPhone', 'MemberAPIController@updateByPhone');
                    Route::delete('/{phone}/force', 'MemberAPIController@forceDelete');
                });
            });

            Route::middleware(['can:view-rule'])->group(function () {
                Route::resource('consumeChopRules', 'ConsumeChopRuleAPIController');
                Route::resource('earnChopRules', 'EarnChopRuleAPIController');
            });

            Route::middleware(['can:view-chops'])->group(function () {
                Route::resource('/chops', 'ChopAPIController');
                Route::prefix('chops')->group(function () {
                    Route::post('/add', 'ChopAPIController@manualAddChops');
                    Route::post('/earn', 'ChopAPIController@earnChops');
                    Route::post('/earn/{id}/void', 'ChopAPIController@voidEarnChops');
                    Route::post('/consume', 'ChopAPIController@consumeChops');
                    Route::post('/consume/{id}/void', 'ChopAPIController@voidConsumeChops');
                    Route::delete('/consume/{id}', 'ChopAPIController@voidConsumeChops');
                });
            });

            Route::middleware(['can:view-prepaidcard'])->prefix('prepaidcards')->group(function () {
                Route::get('/', 'PrepaidCardAPIController@index');
                Route::post('/topup', 'PrepaidCardAPIController@topup');
                Route::post('/payment', 'PrepaidCardAPIController@payment');
                Route::post('/payment/{id}/void', 'PrepaidCardAPIController@voidPayment');
                Route::delete('/payment/{id}', 'PrepaidCardAPIController@voidPayment');
            });

            Route::middleware(['can:view-user'])->group(function () {
                Route::resource('roles', 'RoleAPIController');
                Route::patch('/roles/{id}/permissions', 'RoleAPIController@setPermission');
            });

            Route::middleware(['can:view-branch'])->group(function () {
                Route::resource('branches', 'BranchAPIController');
            });

            Route::middleware(['can:view-rank'])->group(function () {
                Route::prefix('ranks/expiredSettings')->group(function () {
                    Route::get('/', 'RankAPIController@getExpiredSetting');
                    Route::put('/', 'RankAPIController@setExpiredSetting');
                });

                Route::resource('ranks', 'RankAPIController');
                Route::prefix('ranks/{id}/rankDiscounts')->group(function () {
                    Route::get('/', 'RankAPIController@getRankDiscount');
                    Route::put('/', 'RankAPIController@setRankDiscount');
                });

                Route::prefix('ranks/{id}/upgradeSettings')->group(function () {
                    Route::get('/', 'RankAPIController@getRankUpgradeSetting');
                    Route::put('/', 'RankAPIController@setRankUpgradeSetting');
                });

                Route::get('/rankDiscounts', 'RankAPIController@listRankDiscount');
            });

            Route::middleware(['can:view-branch'])->group(function () {
                Route::resource('/transactions', 'TransactionAPIController');
                Route::prefix('transactions')->group(function () {
                    Route::post('/withoutEarnChops', 'TransactionAPIController@newTransactionWithoutEarnChops');
                    Route::post('/{id}/void', 'TransactionAPIController@destroy');
                });
            });

            Route::middleware(['can:view-chops'])->group(function () {
                Route::resource('chopExpiredSettings', 'ChopExpiredSettingAPIController');
            });

            Route::middleware(['can:view-user'])->group(function () {
                Route::resource('users', 'UserAPIController');
            });

            Route::middleware(['can:view-pickup-coupon'])->group(function () {
                Route::post('pickupCoupons/byBranch', 'PickupCouponAPIController@queryByBranch');
                Route::post('pickupCoupons/{id}/giveto', 'PickupCouponAPIController@giveTo');
                Route::post('pickupCoupons/{code}/consume', 'PickupCouponAPIController@consume');
                Route::post('pickupCoupons/batch', 'PickupCouponAPIController@batchStore');
                Route::resource('pickupCoupons', 'PickupCouponAPIController');
            });

            Route::middleware(['can:view-promotion'])->group(function () {
                Route::post('promotions/byPosBranch', 'PromotionAPIController@queryByPOSBranch');
                Route::get('promotions/{code}/byCode', 'PromotionAPIController@queryByCode');
                Route::resource('promotions', 'PromotionAPIController');
            });

            Route::middleware(['can:view-register-chop-rule'])->group(function () {
                Route::get('registerChopRule', 'RegisterChopRuleAPIController@get');
                Route::put('registerChopRule', 'RegisterChopRuleAPIController@update');
            });
        });
    });
});
