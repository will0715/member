<?php

namespace App\Repositories;

use App\Models\RankExpiredSetting;
use App\Repositories\BaseRepository;
use Str;

/**
 * Class RankExpiredSettingRepository
 * @package App\Repositories
 * @version October 5, 2023, 12:00 pm UTC
*/

class RankExpiredSettingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'is_active',
        'calculate_standard',
        'calculate_time_unit',
        'calculate_time_value',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RankExpiredSetting::class;
    }


    /**
     * Get the unique setting
     *
     * @return RankExpiredSetting
     */
    public function getSetting()
    {
        $setting = RankExpiredSetting::first();
        if (!$setting) {
            $setting = RankExpiredSetting::create([
                'is_active' => false,
                'calculate_standard' => RankExpiredSetting::CALCULATE_STANDARD_AMOUNT,
                'calculate_standard_value' => 0,
                'calculate_time_unit' => RankExpiredSetting::CALCULATE_TIME_UNIT_DAY,
                'calculate_time_value' => 0,
            ]);
        }

        return $setting;
    }

    /**
     * Set the unique setting
     *
     * @param array $attributes
     * @return RankExpiredSetting
     */
    public function setSetting(array $attributes)
    {
        $setting = RankExpiredSetting::first();
        if ($setting) {
            $setting->update($attributes);
        } else {
            $setting = RankExpiredSetting::create($attributes);
        }
        return $setting;
    }
}
