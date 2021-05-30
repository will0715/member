<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class BranchPermissonDeniedException extends AccessDeniedHttpException
{
    protected $message = 'Branch permission denied';
    
    public function __construct()
    {
        parent::__construct($this->message);
    }
}