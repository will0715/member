<?php

namespace App\Exceptions;

use Exception;
use Response;

class ResourceNotFoundException extends Exception
{
    private $resource = '';

    public function __construct($resource)
    {
        $this->resource = $resource;
        $this->message = __($resource + ' is not exist');
    }
}