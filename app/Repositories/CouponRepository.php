<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class CouponRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'code' => 'like',
        'status',
        'member_id',
        'coupon_group_id',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Coupon::class;
    }

    public function findByCode($code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function codeExists($code)
    {
        return $this->model->where('code', $code)->exists();
    }

    public function getAvailableCouponsForMember($memberId)
    {
        return $this->model->where('member_id', $memberId)
            ->where('status', Coupon::STATUS_AVAILABLE)
            ->where(function ($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', Carbon::now());
            })
            ->where(function ($query) {
                $query->whereNull('effective_start_at')
                    ->orWhere('effective_start_at', '<=', Carbon::now());
            })
            ->get();
    }

    public function useCoupon($usageData, $id)
    {
        $coupon = $this->findWithoutFail($id);
        if ($coupon && $coupon->isUseable()) {
            $coupon->status = Coupon::STATUS_USED;
            $coupon->used_at = Carbon::now();
            $coupon->usage_data = $usageData;
            $coupon->save();
            return $coupon;
        }
        return null;
    }

    public function disableCoupon($id)
    {
        $coupon = $this->findWithoutFail($id);
        if ($coupon && !$coupon->isUsed()) {
            $coupon->status = Coupon::STATUS_DISABLED;
            $coupon->save();
            return $coupon;
        }
        return null;
    }

    public function getExpiredCoupons()
    {
        return $this->model->where('status', Coupon::STATUS_AVAILABLE)
            ->where('expired_at', '<', Carbon::now())
            ->get();
    }

    public function getCouponsByGroup($couponGroupId)
    {
        return $this->model->where('coupon_group_id', $couponGroupId)->get();
    }

    public function getUseableCoupons()
    {
        $now = Carbon::now();
        return $this->model->where('status', Coupon::STATUS_AVAILABLE)
            ->where(function ($query) use ($now) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('effective_start_at')
                    ->orWhere('effective_start_at', '<=', $now);
            })
            ->get();
    }
}
