<?php

namespace App\Repositories;

use App\Models\PrepaidCard;
use App\Models\PrepaidCardExpiredSetting;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

/**
 * Class PrepaidCardRepository
 * @package App\Repositories
 * @version April 7, 2020, 2:54 pm UTC
*/

class PrepaidCardRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'member_id',
        'branch_id',
        'chops',
        'consume_chops',
        'status',
        'expired_at'
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
        return PrepaidCard::class;
    }

    public function getByMemberId($memberId)
    {
        return $this->findByField('member_id', $memberId)->first();
    }

    public function getByMemberIdWithLock($memberId)
    {

        return $this->model->where('member_id', $memberId)->lockForUpdate()->first();
    }
}
