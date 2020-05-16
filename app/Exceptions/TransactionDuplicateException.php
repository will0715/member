<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Exception;

class TransactionDuplicateException extends Exception
{
    public function __construct($message, $code = ExceptionCode::TRANSACTION_DUPLICATE_EXCEPTION)
    {
        parent::__construct($message, $code);
    }
}