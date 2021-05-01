<?php

namespace App\Utils;

class StringUtil
{
    public static function generateRandomString()
    {
        $bytes = random_bytes(16);
        return bin2hex($bytes);
    }
}
