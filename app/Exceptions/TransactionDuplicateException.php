<?php

namespace App\Exceptions;

use Exception;
use Response;

class TransactionDuplicateException extends Exception
{
    private $orderId = '';

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
        $this->message = __($orderId . ' is already exist');
    }
}