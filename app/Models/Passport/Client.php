<?php

namespace App\Models\Passport;

use Laravel\Passport\Client as PassportClient;

class Client extends PassportClient
{
    // ...
    public $table = 'public.oauth_clients';
}