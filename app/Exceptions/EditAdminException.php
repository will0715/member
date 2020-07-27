<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class EditAdminException extends ConflictHttpException
{
    protected $message = 'Can not edit admin role';
    
    public function __construct()
    {
        parent::__construct($this->message);
    }
}