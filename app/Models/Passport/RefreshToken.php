<?php

namespace App\Models\Passport;

use Laravel\Passport\RefreshToken as PassportRefreshToken;

class RefreshToken extends PassportRefreshToken
{
    // ...
    public $table = 'public.oauth_refresh_tokens';
}