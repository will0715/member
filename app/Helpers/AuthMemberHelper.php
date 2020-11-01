<?php

namespace App\Helpers;

class AuthMemberHelper {
    static public function setAuthMember($member)
    {
        config(['_member' => $member]);
    }

    static public function getMember()
    {
        return config('_member');
    }
}