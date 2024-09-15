<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponGroup extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'coupon_groups';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    const CALCULATE_TIME_UNIT_FIXED = 'FIXED';
    const CALCULATE_TIME_UNIT_CLAIM = 'CLAIM';

    public $fillable = [
        'name',
        'prefix_code',
        'limit_branch',
        'calculate_time_unit',
        'fixed_start_time',
        'fixed_end_time',
        'valid_days_after_claim',
        'can_trigger_others',
        'trigger_condition',
        'content'
    ];

    protected $casts = [
        'id' => 'string',
        'name' => 'string',
        'prefix_code' => 'string',
        'limit_branch' => 'boolean',
        'calculate_time_unit' => 'string',
        'fixed_start_time' => 'datetime',
        'fixed_end_time' => 'datetime',
        'valid_days_after_claim' => 'integer',
        'can_trigger_others' => 'boolean',
        'trigger_condition' => 'array',
        'content' => 'array'
    ];

    public static $rules = [
        'name' => 'required',
        'prefix_code' => 'required|unique:coupon_groups,prefix_code',
        'calculate_time_unit' => 'required|in:FIXED,CLAIM',
        'trigger_condition' => 'required',
        'content' => 'required',
    ];

    public function limitBranches()
    {
        return $this->belongsToMany(\App\Models\Branch::class, 'coupon_group_branch');
    }

    public function coupons()
    {
        return $this->hasMany(\App\Models\Coupon::class, 'coupon_group_id');
    }
}
