<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class TransactionDuplicateException extends ConflictHttpException
{
    protected $message = ' is already exists';
    
    public function __construct($orderId)
    {
        parent::__construct($orderId . $this->message);
    }
}