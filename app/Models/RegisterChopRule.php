<?php

namespace App\Models;

use App\Models\BaseModel as Model;

class RegisterChopRule extends Model
{

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'register_chop_rules';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'name',
        'description',
        'rule_chops',
        'is_active',
    ];

    protected $attributes = [
        'name' => '',
        'is_active' => false,
        'rule_chops' => 0,
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'name' => 'string',
        'description' => 'string',
        'rule_chops' => 'double',
        'is_active' => 'boolean',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'rule_chops' => 'required',
        'is_active' => 'required'
    ];
}
