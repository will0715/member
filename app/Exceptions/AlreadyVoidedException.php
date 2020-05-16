<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Exception;

class AlreadyVoidedException extends Exception
{
    private $voidedRecord = null;

    public function __construct($message, $voidedRecord, $code = ExceptionCode::ALREADY_VOIDED_EXCEPTION)
    {
        $this->voidedRecord = $voidedRecord;
        $message = sprintf('[AlreadyVoided]%s', $message);
        parent::__construct($message, $code);
    }
}