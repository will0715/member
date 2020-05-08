<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EarnChopRule
 * @package App\Models
 * @version April 7, 2020, 12:42 pm UTC
 *
 * @property \App\Models\Rank rank
 * @property string rank_id
 * @property string name
 * @property string description
 * @property string payment_type
 * @property string type
 * @property number rule_unit
 * @property number rule_chops
 * @property string exclude_product
 */
class EarnChopRule extends Model
{

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'earn_chop_rules';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['activated_at', 'expired_at', 'deleted_at'];

    public $fillable = [
        'rank_id',
        'name',
        'description',
        'payment_type',
        'type',
        'rule_unit',
        'rule_chops',
        'exclude_product',
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
        'rule_unit' => 'float',
        'rule_chops' => 'float',
        'exclude_product' => 'string',
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
        'payment_type' => 'required',
        'type' => 'required',
        'rule_unit' => 'required',
        'rule_chops' => 'required',
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
