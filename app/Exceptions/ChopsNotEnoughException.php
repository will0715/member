<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Exception;

class ChopsNotEnoughException extends Exception
{
    public function __construct($message, $code = ExceptionCode::CHOPS_NOT_ENOUGH_EXCEPTION)
    {
        $message = sprintf('[ChopsNotEnough]%s', $message);
        parent::__construct($message, $code);
    }
}