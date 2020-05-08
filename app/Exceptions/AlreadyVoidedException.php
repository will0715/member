<?php

namespace App\Exceptions;

use Exception;
use Response;

class AlreadyVoidedException extends Exception
{
    private $voidedRecord = null;

    public function __construct($voidedRecord)
    {
        $this->voidedRecord = $voidedRecord;
        $this->message = __('record already voided');
    }
}