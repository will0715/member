<?php

namespace App\Helpers;

class CustomerHelper {
    static public function setCustomer($customer)
    {
        config(['customer' => $customer]);
    }

    static public function getCustomer()
    {
        return config('customer');
    }
}