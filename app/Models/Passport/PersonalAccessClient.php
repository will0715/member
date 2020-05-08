<?php

namespace App\Models\Passport;

use Laravel\Passport\PersonalAccessClient as PassportPersonalAccessClient;

class PersonalAccessClient extends PassportPersonalAccessClient
{
    // ...
    public $table = 'public.oauth_personal_access_clients';
}