<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PickupCouponConsumedHistory
 * @package App\Models
 * @version June 20, 2017, 4:21 pm UTC
 */
class PickupCouponConsumedHistory extends Model
{
    
    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'pickup_coupon_consumed_histories';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['consumed_at'];


    public $fillable = [
        'pickup_coupon_id',
        'consumed_quantity',
        'consumed_branch',
        'remark',
        'consumed_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'pickup_coupon_id' => 'string',
        'consumed_quantity' => 'integer',
        'consumed_branch' => 'string',
        'remark' => 'string',
        'consumed_at' => 'datetime',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'consumed_quantity' => 'required',
        'consumed_branch' => 'required',
        'consumed_at' => 'required',
    ];

    public function pickupCoupon()
    {
        return $this->belongsToMany(\App\Models\PickupCoupon::class);
    }
}
