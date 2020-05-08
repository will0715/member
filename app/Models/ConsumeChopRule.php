<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ConsumeChopRule
 * @package App\Models
 * @version April 7, 2020, 12:40 pm UTC
 *
 * @property \App\Models\Rank rank
 * @property string rank_id
 * @property string type
 * @property number chops_per_unit
 * @property number unit_per_amount
 * @property boolean earn_chops_after_consume
 */
class ConsumeChopRule extends Model
{

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'consume_chop_rules';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['activated_at', 'expired_at', 'deleted_at'];


    public $fillable = [
        'rank_id',
        'name',
        'description',
        'payment_type',
        'type',
        'chops_per_unit',
        'unit_per_amount',
        'earn_chops_after_consume',
        'activated_at',
        'expired_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'rank_id' => 'string',
        'name' => 'string',
        'description' => 'string',
        'payment_type' => 'string',
        'type' => 'string',
        'chops_per_unit' => 'float',
        'unit_per_amount' => 'float',
        'earn_chops_after_consume' => 'boolean',
        'activated_at' => 'datetime',
        'expired_at' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'rank_id' => 'required',
        'name' => 'required',
        'description' => 'required',
        'payment_type' => 'required',
        'type' => 'required',
        'chops_per_unit' => 'required',
        'activated_at' => 'required|date',
        'expired_at' => 'required|date'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function rank()
    {
        return $this->belongsTo(\App\Models\Rank::class, 'rank_id');
    }
}
