<?php

namespace App\Repositories;

use App\Models\ChopRecord;
use App\Constants\ChopRecordConstant;
use App\Repositories\BaseRepository;
use Arr;

/**
 * Class ChopRecordRepository
 * @package App\Repositories
 * @version April 12, 2020, 8:24 am UTC
*/

class ChopRecordRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'member.first_name' => 'like',
        'member.last_name' => 'like',
        'member.phone' => 'like',
        'branch.name',
        'transaction_no',
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
        return ChopRecord::class;
    }

    public function newManualChopRecord($data)
    {
        return $this->create([
            'member_id' => $data['member_id'],
            'branch_id' => $data['branch_id'],
            'type' => ChopRecordConstant::CHOP_RECORD_ADD_CHOPS,
            'chops' => $data['chops'],
            'consume_chops' => 0,
            'transaction_no' => Arr::get($data, 'transaction_no'),
            'remark' => Arr::get($data, 'remark'),
        ]);
    }

    public function newEarnChopRecord($data)
    {
        return $this->create([
            'member_id' => $data['member_id'],
            'branch_id' => $data['branch_id'],
            'type' => ChopRecordConstant::CHOP_RECORD_EARN_CHOPS,
            'chops' => $data['chops'],
            'consume_chops' => 0,
            'rule_id' => $data['rule_id'],
            'transaction_no' => Arr::get($data, 'transaction_no'),
            'remark' => Arr::get($data, 'remark'),
        ]);
    }

    public function voidEarnChopRecord($data)
    {
        return  $this->create([
            'member_id' => $data['member_id'],
            'branch_id' => $data['branch_id'],
            'type' => ChopRecordConstant::CHOP_RECORD_VOID_EARN_CHOPS,
            'chops' => $data['chops'],
            'consume_chops' => 0,
            'void_id' => $data['void_id'],
            'remark' => Arr::get($data, 'remark'),
        ]);
    }

    public function newConsumeChopRecord($data)
    {
        return $this->create([
            'member_id' => $data['member_id'],
            'branch_id' => $data['branch_id'],
            'type' => ChopRecordConstant::CHOP_RECORD_CONSUME_CHOPS,
            'chops' => 0,
            'consume_chops' => $data['consume_chops'],
            'rule_id' => $data['rule_id'],
            'transaction_no' => Arr::get($data, 'transaction_no'),
            'remark' => Arr::get($data, 'remark'),
        ]);
    }

    public function voidConsumeChopRecord($data)
    {
        return $this->create([
            'member_id' => $data['member_id'],
            'branch_id' => $data['branch_id'],
            'type' => ChopRecordConstant::CHOP_RECORD_VOID_CONSUME_CHOPS,
            'chops' => 0,
            'consume_chops' => $data['consume_chops'],
            'void_id' => $data['void_id'],
            'remark' => Arr::get($data, 'remark'),
        ]);
    }

    public function findManualAddChops()
    {
        return $this->findWhereIn('type', [ChopRecordConstant::CHOP_RECORD_ADD_CHOPS]);
    }

    public function findEarnChops()
    {
        return $this->findWhereIn('type', [ChopRecordConstant::CHOP_RECORD_EARN_CHOPS]);
    }

    public function findVoidEarnChops()
    {
        return $this->findWhereIn('type', [ChopRecordConstant::CHOP_RECORD_VOID_EARN_CHOPS]);
    }

    public function findConsumeChops()
    {
        return $this->findWhereIn('type', [ChopRecordConstant::CHOP_RECORD_CONSUME_CHOPS]);
    }

    public function findVoidConsumeChops()
    {
        return $this->findWhereIn('type', [ChopRecordConstant::CHOP_RECORD_VOID_CONSUME_CHOPS]);
    }

    public function findAllAddChops()
    {
        return $this->findWhereIn('type', [
            ChopRecordConstant::CHOP_RECORD_ADD_CHOPS, 
            ChopRecordConstant::CHOP_RECORD_EARN_CHOPS,
            ChopRecordConstant::CHOP_RECORD_VOID_EARN_CHOPS
        ]);
    }

    public function findAllConsumeChops()
    {
        return $this->findWhereIn('type', [
            ChopRecordConstant::CHOP_RECORD_CONSUME_CHOPS, 
            ChopRecordConstant::CHOP_RECORD_VOID_CONSUME_CHOPS
        ]);
    }
}
