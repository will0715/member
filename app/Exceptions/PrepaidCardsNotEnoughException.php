<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PrepaidCardsNotEnoughException extends BadRequestHttpException
{
    protected $message = 'Balance not enough';
    
    public function __construct()
    {
        parent::__construct($this->message);
    }
}