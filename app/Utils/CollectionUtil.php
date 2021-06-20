<?php

namespace App\Utils;

class CollectionUtil
{
    public static function isEmpty($array)
    {
        return collect($array)->isEmpty();
    }

    public static function isNotEmpty($array)
    {
        return collect($array)->isNotEmpty();
    }
}
