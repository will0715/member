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
        'member_id',
        'branch_id',
        'type',
        'chops',
        'consume_chops'
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
            'transaction_id' => $data['transaction_id'],
            'rule_id' => $data['rule_id']
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
            'void_id' => $data['void_id']
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
            'void_id' => $data['void_id']
        ]);
    }

    public function voidRecord($id)
    {
        $record = $this->find($id);
        if ($record->voidRecord) {
            return null;
        }
        $voidRecord = null;
        switch ($record->type) {
            case ChopRecordConstant::CHOP_RECORD_CONSUME_CHOPS:
                $voidRecord = $this->create([
                    'member_id' => $record->member_id,
                    'branch_id' => $record->branch_id,
                    'type' => ChopRecordConstant::CHOP_RECORD_VOID_CONSUME_CHOPS,
                    'chops' => 0,
                    'consume_chops' => -1 * $record->consume_chops,
                    'void_id' => $record->id
                ]);
                break;
                
            case ChopRecordConstant::CHOP_RECORD_EARN_CHOPS:
                $voidRecord = $this->create([
                    'member_id' => $record->member_id,
                    'branch_id' => $record->branch_id,
                    'type' => ChopRecordConstant::CHOP_RECORD_VOID_EARN_CHOPS,
                    'chops' => -1 * $record->chops,
                    'consume_chops' => 0,
                    'void_id' => $record->id
                ]);
                break;
            
            default:
                break;
        }
        if ($voidRecord) {
            return $voidRecord;
        }

        return null;
    }
}
