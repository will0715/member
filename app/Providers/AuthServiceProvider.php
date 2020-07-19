<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use App\Models\Passport\AuthCode;
use App\Models\Passport\Client;
use App\Models\Passport\PersonalAccessClient;
use App\Models\Passport\Token;
use App\Models\Passport\RefreshToken;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(null);

        // Passport::tokensCan([
        //     'roles' => 'roles',
        //     'customers' => 'customers',
        // ]);

        Passport::useTokenModel(Token::class);
        Passport::useClientModel(Client::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);
        Passport::useRefreshTokenModel(RefreshToken::class);

        // Passport::tokensExpireIn(now()->addDays(15));
    
        // Passport::refreshTokensExpireIn(now()->addDays(30));
    
        if (config('app.debug')) {
            Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        } else {
            Passport::personalAccessTokensExpireIn(now()->addMonths(1));
        }
    }
}
