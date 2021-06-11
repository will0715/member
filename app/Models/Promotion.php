<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Promotion
 * @package App\Models
 * @version June 20, 2021, 4:21 pm UTC
 */
class Promotion extends Model
{
    use SoftDeletes;
    
    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'promotions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'code',
        'type',
        'sequence',
        'activated_date_start',
        'activated_date_end',
        'activated_time_start',
        'activated_time_end',
        'activated_weekday',
        'activated_monthday',
        'can_trigger_others',
        'trigger_condition',
        'content'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'code' => 'string',
        'name' => 'string',
        'type' => 'string',
        'sequence' => 'integer',
        'activated_date_start' => 'string',
        'activated_date_end' => 'string',
        'activated_time_start' => 'string',
        'activated_time_end' => 'string',
        'activated_weekday' => 'string',
        'activated_monthday' => 'string',
        'can_trigger_others' => 'boolean',
        'trigger_condition' => 'array',
        'content' => 'array'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'code' => 'required|unique:promotions,code',
        'name' => 'required',
        'type' => 'required',
        'sequence' => 'required|integer',
        'activated_date_start' => 'required|date_format:Y-m-d',
        'activated_date_end' => 'required|date_format:Y-m-d',
        'activated_time_start' => 'required|date_format:H:i:s',
        'activated_time_end' => 'required|date_format:H:i:s',
        'trigger_condition' => 'required',
        'content' => 'required',
    ];

    public function limitRanks()
    {
        return $this->belongsToMany(\App\Models\Rank::class, 'promotion_rank');
    }

    public function limitBranches()
    {
        return $this->belongsToMany(\App\Models\Branches::class, 'promotion_branch');
    }
}
