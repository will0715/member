<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PickupCoupon
 * @package App\Models
 * @version June 20, 2017, 4:21 pm UTC
 */
class PickupCoupon extends Model
{
    use SoftDeletes;
    
    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'pickup_coupons';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at', 'expired_at', 'last_consumed_at'];


    public $fillable = [
        'phone',
        'product_name',
        'product_no',
        'code',
        'quantity',
        'consumed_quantity',
        'price',
        'condiments',
        'limit_branch',
        'remark',
        'expired_at',
        'last_consumed_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'phone' => 'string',
        'product_name' => 'string',
        'product_no' => 'string',
        'code' => 'string',
        'quantity' => 'integer',
        'consumed_quantity' => 'integer',
        'price' => 'float',
        'condiments' => 'string',
        'limit_branch' => 'boolean',
        'remark' => 'string',
        'expired_at' => 'datetime',
        'last_consumed_at' => 'datetime',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_name' => 'required',
        'product_no' => 'required',
        'code' => 'required|unique:App\Models\PickupCoupon,code',
        'quantity' => 'required',
        'consumed_quantity' => 'required',
        'price' => 'required',
    ];

    public function consumedHistory()
    {
        return $this->hasMany(\App\Models\PickupCouponConsumedHistory::class);
    }

    public function limitBranches()
    {
        return $this->belongsToMany(\App\Models\Branch::class, 'pickup_coupon_branch');
    }
}
