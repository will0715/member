<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SMSLog
 * @package App\Models
 * @version June 20, 2017, 4:21 pm UTC
 */
class SMSLog extends Model
{   
    const DB_CONNECTION = 'pgsql';
    protected $connection = 'pgsql';

    public $table = 'sms_logs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $fillable = [
        'provider', 
        'phone', 
        'content', 
    ];

    protected $attributes = [
        'provider' => 'twilio',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'provider' => 'string',
        'phone' => 'string',
        'content' => 'array',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'provider' => 'required',
        'phone' => 'required',
        'content' => 'required',
    ];
}
