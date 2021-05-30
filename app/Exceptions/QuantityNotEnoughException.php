<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class QuantityNotEnoughException extends BadRequestHttpException
{
    protected $message = 'Quantity not enough';
    
    public function __construct()
    {
        parent::__construct($this->message);
    }
}