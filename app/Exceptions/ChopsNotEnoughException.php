<?php

namespace App\Exceptions;

use Exception;
use Response;

class ChopsNotEnoughException extends Exception
{
    public function __construct()
    {
        $this->message = __('chops not enough');
    }
}