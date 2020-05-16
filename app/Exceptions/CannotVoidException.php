<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Exception;

class CannotVoidException extends Exception
{
    private $voidedRecord = null;

    public function __construct($message, $voidedRecord, $code = ExceptionCode::CANNOT_VOID_EXCEPTION)
    {
        $this->voidedRecord = $voidedRecord;
        $message = sprintf('[CannotVoid]%s', $message);
        parent::__construct($message, $code);
    }
}