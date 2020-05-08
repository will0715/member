<?php

namespace App\Utils;

class BooleanUtil
{
    public static function parseString($string)
    {
        return filter_var($string, FILTER_VALIDATE_BOOLEAN);
    }
}
