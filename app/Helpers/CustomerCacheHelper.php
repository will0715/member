<?php

namespace App\Helpers;

use Cache;

class CustomerCacheHelper {
    static public function setPrefix($customer)
    {
        config(['cache.prefix' => $customer]);
        Cache::setPrefix($customer);
    }
}