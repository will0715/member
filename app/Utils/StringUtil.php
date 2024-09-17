<?php

namespace App\Utils;

class StringUtil
{
    public static function generateRandomString()
    {
        $bytes = random_bytes(16);
        return bin2hex($bytes);
    }

    public static function generateNumericCode($length)
    {
        $digits = '0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $digits[rand(0, strlen($digits) - 1)];
        }
        return $code;
    }
}
