<?php

namespace App\Repositories;

use App\Models\RankUpgradeSetting;
use App\Repositories\BaseRepository;
use Str;

/**
 * Class RankUpgradeSettingRepository
 * @package App\Repositories
 * @version October 5, 2023, 12:00 pm UTC
*/

class RankUpgradeSettingRepository extends BaseRepository
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
        return RankUpgradeSetting::class;
    }

    public function getDefaultRankUpgradeSetting($rankId)
    {
        return RankUpgradeSetting::create([
            'rank_id' => $rankId,
            'is_active' => false,
            'calculate_standard' => RankUpgradeSetting::CALCULATE_STANDARD_AMOUNT,
            'calculate_standard_value' => 0,
            'calculate_time_unit' => RankUpgradeSetting::CALCULATE_TIME_UNIT_DAY,
            'calculate_time_value' => 0,
        ]);
    }

    public function findByRankId($rankId)
    {
        return $this->model->where('rank_id', $rankId)->first();
    }
}
