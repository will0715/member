<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RankExpiredSetting
 * @package App\Models
 * @version October 5, 2023, 12:00 pm UTC
 *
 * @property boolean is_active
 * @property string calculate_standard
 * @property string calculate_time_unit
 * @property integer calculate_time_value
 */
class RankExpiredSetting extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'rank_expired_settings';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'is_active',
        'calculate_standard',
        'calculate_standard_value',
        'calculate_time_unit',
        'calculate_time_value',
    ];

    // Enum for calculate_standard
    const CALCULATE_STANDARD_AMOUNT = 'AMOUNT';
    const CALCULATE_STANDARD_TIMES = 'TIMES';

    // Enum for calculate_time_unit
    const CALCULATE_TIME_UNIT_DAY = 'DAY';
    const CALCULATE_TIME_UNIT_MONTH = 'MONTH';
    const CALCULATE_TIME_UNIT_YEAR = 'YEAR';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'calculate_standard' => 'string|in:AMOUNT,TIMES',
        'calculate_standard_value' => 'decimal:2',
        'calculate_time_unit' => 'string|in:DAY,MONTH,YEAR',
        'calculate_time_value' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    /**
     * Get the calculate_standard options.
     *
     * @return array
     */
    public static function getCalculateStandardOptions()
    {
        return [
            self::CALCULATE_STANDARD_AMOUNT,
            self::CALCULATE_STANDARD_PERCENTAGE,
        ];
    }

    /**
     * Get the calculate_time_unit options.
     *
     * @return array
     */
    public static function getCalculateTimeUnitOptions()
    {
        return [
            self::CALCULATE_TIME_UNIT_DAY,
            self::CALCULATE_TIME_UNIT_MONTH,
            self::CALCULATE_TIME_UNIT_YEAR,
        ];
    }
}
