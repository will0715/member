<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class AlreadyVoidedException extends ConflictHttpException
{
    protected $message = 'Already voided';
    
    public function __construct()
    {
        parent::__construct($this->message);
    }
}