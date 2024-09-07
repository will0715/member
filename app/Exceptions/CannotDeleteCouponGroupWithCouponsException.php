<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CannotDeleteCouponGroupWithCouponsException extends ConflictHttpException
{
    protected $message = 'Can not delete coupon group with coupons';

    public function __construct()
    {
        parent::__construct($this->message);
    }
}
