<?php

namespace App\Models;

use App\Models\BaseModel as Model;

/**
 * Class Config
 * @package App\Models
 * @version June 20, 2017, 4:21 pm UTC
 */
class Config extends Model
{
    
    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'configs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'key',
        'value'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'key' => 'string',
        'value' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'key' => 'required|unique:configs,key',
        'value' => 'required',
    ];
}
