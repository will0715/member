<?php

namespace App\Repositories;

use App\Models\Member;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Arr;

/**
 * Class MemberRepository
 * @package App\Repositories
 * @version April 3, 2020, 4:25 am UTC
*/

class MemberRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'phone',
        'first_name',
        'last_name',
        'gender',
        'email',
        'address',
        'remark',
        'card_carrier_no',
        'invoice_carrier_no'
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
        return Member::class;
    }

    public function newMember($data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->create($data);
    }

    public function updateMember($data, $id)
    {
        $password = Arr::get($data, 'password');
        if ($password && !empty($password)) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        return $this->update($data, $id);
    }

    public function findByPhone($phone)
    {
        return $this->findByField('phone', $phone)->first();
    }

    public function findValid()
    {
        return $this->findWhere(['status' => 1]);
    }
}
