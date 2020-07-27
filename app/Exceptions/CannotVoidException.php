<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CannotVoidException extends ConflictHttpException
{
    protected $message = 'Can not void this record';
    
    public function __construct()
    {
        parent::__construct($this->message);
    }
}