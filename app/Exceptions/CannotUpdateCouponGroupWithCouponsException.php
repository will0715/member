<?php

namespace App\Exceptions;

use App\Constants\ExceptionCode;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CannotUpdateCouponGroupWithCouponsException extends ConflictHttpException
{
    protected $message = 'Can not update coupon group with coupons';

    public function __construct()
    {
        parent::__construct($this->message);
    }
}
