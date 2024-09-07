<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Coupon extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'coupons';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_AVAILABLE = 'AVAILABLE';
    const STATUS_USED = 'USED';
    const STATUS_DISABLED = 'DISABLED';

    protected $dates = ['deleted_at', 'claimed_at', 'used_at', 'expired_at', 'effective_start_at'];

    public $fillable = [
        'coupon_group_id',
        'member_id',
        'code',
        'status',
        'claimed_at',
        'used_at',
        'expired_at',
        'effective_start_at',
        'usage_data'
    ];

    protected $casts = [
        'id' => 'string',
        'coupon_group_id' => 'string',
        'member_id' => 'string',
        'code' => 'string',
        'status' => 'string',
        'claimed_at' => 'datetime',
        'used_at' => 'datetime',
        'expired_at' => 'datetime',
        'effective_start_at' => 'datetime',
        'usage_data' => 'array'
    ];

    public static $rules = [
        'coupon_group_id' => 'required|exists:coupon_groups,id',
        'code' => 'required|unique:coupons,code',
        'status' => 'required|in:AVAILABLE,USED,DISABLED',
    ];

    public function couponGroup()
    {
        return $this->belongsTo(\App\Models\CouponGroup::class);
    }

    public function member()
    {
        return $this->belongsTo(\App\Models\Member::class);
    }

    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isUsed()
    {
        return $this->status === self::STATUS_USED;
    }

    public function isUseable()
    {
        $now = Carbon::now();

        // 檢查優惠券是否可用
        if (!$this->isAvailable()) {
            return false;
        }

        // 檢查是否在有效期內
        if ($this->expired_at && $now->isAfter($this->expired_at)) {
            return false;
        }

        // 檢查是否已經開始生效
        if ($this->effective_start_at && $now->isBefore($this->effective_start_at)) {
            return false;
        }

        // 檢查優惠券組是否仍然有效（可能需要根據實際情況調整）
        if ($this->couponGroup && method_exists($this->couponGroup, 'isActive')) {
            if (!$this->couponGroup->isActive()) {
                return false;
            }
        }

        return true;
    }
}
