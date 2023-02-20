<?php

namespace App\Repositories;

use App\Models\Member;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Arr;
use DB;

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
        'phone' => 'like',
        'first_name' => 'like',
        'last_name' => 'like',
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

    public function listWithChopsCount($attribute = [])
    {
        $this->applyCriteria();
        $this->applyScope();

        $members = $this->model->withCount(
            $this->basicInfo()
        );

        $this->resetModel();
        $this->resetScope();

        return $members;
    }

    public function findWithChopsBalance($id)
    {
        $this->applyCriteria();
        $this->applyScope();

        $member = $this->model->withCount(
            $this->basicInfo()
        )->find($id);

        $this->resetModel();
        $this->resetScope();

        return $member;
    }

    private function basicInfo()
    {
        $now = Carbon::now();

        return [
            'chops AS chops_total' => function ($query) use ($now) {
                $query->select(DB::raw("SUM(chops) as total"))
                        ->where('expired_at', '>=', $now)
                        ->orWhereNull('expired_at');
            },
            'prepaidcard AS prepaidcard_balance' => function ($query) {
                $query->select('balance');
            },
        ];
    }
}
