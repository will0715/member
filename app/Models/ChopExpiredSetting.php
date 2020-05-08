<?php

namespace App\Models;

use App\Models\BaseModel as Model;

/**
 * Class ChopExpiredSetting
 * @package App\Models
 * @version April 7, 2020, 2:49 pm UTC
 *
 * @property integer expired_date
 */
class ChopExpiredSetting extends Model
{

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'chop_expired_setting';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'expired_date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'expired_date' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'expired_date' => 'required'
    ];

    
}
