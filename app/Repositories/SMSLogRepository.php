<?php

namespace App\Repositories;

use App\Models\SMSLog;
use App\Repositories\BaseRepository;

/**
 * Class SMSLogRepository
 * @package App\Repositories
 * @version April 2, 2020, 4:32 pm UTC
*/

class SMSLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'provider', 
        'phone', 
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
        return SMSLog::class;
    }
}
