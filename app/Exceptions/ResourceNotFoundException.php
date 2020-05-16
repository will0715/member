<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Exception;

class ResourceNotFoundException extends Exception
{
    public function __construct($message, $code = ExceptionCode::RESOURCE_NOT_FOUND_EXCEPTION)
    {
        parent::__construct($message, $code);
    }
}