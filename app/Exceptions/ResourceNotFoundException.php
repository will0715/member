<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResourceNotFoundException extends NotFoundHttpException
{
}