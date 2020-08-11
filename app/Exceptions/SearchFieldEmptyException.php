<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class SearchFieldEmptyException extends UnprocessableEntityHttpException
{
    protected $message = 'Must input search field';
    
    public function __construct()
    {
        parent::__construct($this->message);
    }
}