<?php

namespace App\Utils;

use \Firebase\JWT\JWT;
use Carbon\Carbon;

class MemberAuthToken
{
    public static function makeMemberAuthToken($member, $expiredDays = null)
    {
        $expiredDays = $expiredDays ?: config('jwt.expired_days');
        $time = Carbon::now();

        return [
            'access_token' => self::generateJWT($member, $expiredDays),
            'expires_at' => Carbon::now()->add($expiredDays, 'days'),
        ];
    }
    
    public static function generateJWT($member, $expiredDays)
    {

        $key = self::getKey();
        $payload = self::makePayload($member, $expiredDays);

        $jwt = JWT::encode($payload, $key);

        return $jwt;
    }
    
    public static function decode($token)
    {
        $key = self::getKey();

        $decoded = JWT::decode($token, $key, array('HS256'));

        return $decoded;
    }

    private static function getKey()
    {
        $key = config('jwt.member_login_key');

        return $key;
    }

    private static function makePayload($member, $expiredDays)
    {
        return [
            "iss" => config('app.url'),
            "aud" => config('app.url'),
            "iat" => Carbon::now()->timestamp,
            "exp" => Carbon::now()->add($expiredDays, 'days')->timestamp,
            'member_id' => $member->id,
        ];
    }
}
