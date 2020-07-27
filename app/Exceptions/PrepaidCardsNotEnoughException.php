<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class PrepaidCardsNotEnoughException extends ConflictHttpException
{
    protected $message = 'Balance not enough';
    
    public function __construct()
    {
        parent::__construct($this->message);
    }
}