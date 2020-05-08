<?php

namespace App\Models\Passport;

use Laravel\Passport\AuthCode as PassportAuthCode;

class AuthCode extends PassportAuthCode
{
    // ...
    public $table = 'public.oauth_auth_codes';
}