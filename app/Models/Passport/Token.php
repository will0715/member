<?php

namespace App\Models\Passport;

use Laravel\Passport\Token as PassportToken;

class Token extends PassportToken
{
    // ...
    public $table = 'public.oauth_access_tokens';
}