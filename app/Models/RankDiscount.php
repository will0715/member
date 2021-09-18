<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\RankDiscountConstant;

/**
 * Class RankDiscount
 * @package App\Models
 * @version June 20, 2021, 4:21 pm UTC
 */
class RankDiscount extends Model
{
    use SoftDeletes;
    
    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'rank_discounts';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const IS_UNACTIVE = false;
    const DEFAULT_CONTENT = [
        'type' => RankDiscountConstant::CONTENT_DISCOUNT_AMOUNT,
        'price' => 0
    ];

    protected $dates = ['deleted_at'];


    public $fillable = [
        'rank_id',
        'is_active',
        'content'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'rank_id' => 'string',
        'is_active' => 'boolean',
        'content' => 'array'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'is_active' => 'required|boolean',
        'content' => 'required',
    ];

    public function rank()
    {
        return $this->belongsTo(\App\Models\Rank::class);
    }
}
