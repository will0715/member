<?php

namespace App\Repositories;

use App\Models\ChopExpiredSetting;
use App\Repositories\BaseRepository;

/**
 * Class ChopExpiredSettingRepository
 * @package App\Repositories
 * @version April 7, 2020, 2:49 pm UTC
*/

class ChopExpiredSettingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'expired_date'
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
        return ChopExpiredSetting::class;
    }

    public function getChopsExpiredSetting($branchId = null)
    {
        return ChopExpiredSetting::first();
    }
}
