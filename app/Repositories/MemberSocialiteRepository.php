<?php

namespace App\Repositories;

use App\Models\MemberSocialite;
use App\Repositories\BaseRepository;

/**
 * Class MemberSocialiteRepository
 * @package App\Repositories
 * @version April 7, 2020, 2:49 pm UTC
*/

class MemberSocialiteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'socialite_provider',
        'socialite_user_id',
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
        return MemberSocialite::class;
    }

    public function findBySocialiteUserId($socialiteProvider, $userId)
    {
        return MemberSocialite::where([
            'socialite_provider' => $socialiteProvider,
            'socialite_user_id' => $userId,
        ])->first();
    }
}
