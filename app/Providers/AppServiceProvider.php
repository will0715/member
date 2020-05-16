<?php

namespace App\Providers;

use App\ServiceManagers\MemberChopServiceManager;
use App\ServiceManagers\MemberPrepaidCardServiceManager;
use App\Services\BranchService;
use App\Services\ChopService;
use App\Services\MemberService;
use App\Services\PrepaidCardService;
use App\Services\ReportService;
use App\Services\TransactionService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('App\ServiceManagers\MemberChopServiceManager', function ($app) {
            return new MemberChopServiceManager();
        });
        $this->app->singleton('App\ServiceManagers\MemberPrepaidCardServiceManager', function ($app) {
            return new MemberPrepaidCardServiceManager();
        });
        $this->app->singleton('App\Services\BranchService', function ($app) {
            return new BranchService();
        });
        $this->app->singleton('App\Services\ChopService', function ($app) {
            return new ChopService();
        });
        $this->app->singleton('App\Services\MemberService', function ($app) {
            return new MemberService();
        });
        $this->app->singleton('App\Services\PrepaidCardService', function ($app) {
            return new PrepaidCardService();
        });
        $this->app->singleton('App\Services\ReportService', function ($app) {
            return new ReportService();
        });
        $this->app->singleton('App\Services\TransactionService', function ($app) {
            return new TransactionService();
        });

        Resource::withoutWrapping();
    }
}
