<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ChopsNotEnoughException extends ConflictHttpException
{
    protected $message = 'Chops not enough';
    
    public function __construct()
    {
        parent::__construct($this->message);
    }
}