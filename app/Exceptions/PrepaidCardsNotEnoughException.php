<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Exception;

class PrepaidCardsNotEnoughException extends Exception
{
    public function __construct($message, $code = ExceptionCode::PREPAIDCARD_NOT_ENOUGH_EXCEPTION)
    {
        $message = sprintf('[PrepaidCardsNotEnough]%s', $message);
        parent::__construct($message, $code);
    }
}