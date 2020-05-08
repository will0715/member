<?php

namespace App\Repositories;

use App\Models\ChopRecord;
use App\Repositories\BaseRepository;
use Arr;

/**
 * Class ChopRecordRepository
 * @package App\Repositories
 * @version April 12, 2020, 8:24 am UTC
*/

class ChopRecordRepository extends BaseRepository
{
    const ADD_CHOPS = 'ADD_CHOPS';
    const EARN_CHOPS = 'EARN_CHOPS';
    const CONSUME_CHOPS = 'CONSUME_CHOPS';
    const VOID_EARN_CHOPS = 'VOID_EARN_CHOPS';
    const VOID_CONSUME_CHOPS = 'VOID_CONSUME_CHOPS';

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
        $member = Arr::get($data, 'member');
        $branch = Arr::get($data, 'branch');
        $chops = Arr::get($data, 'chops');

        return $this->create([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'type' => self::ADD_CHOPS,
            'chops' => $chops,
            'consume_chops' => 0,
        ]);
    }

    public function newEarnChopRecord($data)
    {
        $member = Arr::get($data, 'member');
        $branch = Arr::get($data, 'branch');
        $chops = Arr::get($data, 'chops');
        $transaction = Arr::get($data, 'transaction');
        $earnChopRule = Arr::get($data, 'earnChopRule');

        return $this->create([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'type' => self::EARN_CHOPS,
            'chops' => $chops,
            'consume_chops' => 0,
            'transaction_id' => $transaction->id,
            'rule_id' => $earnChopRule->id
        ]);
    }

    public function newConsumeChopRecord($data)
    {
        $member = Arr::get($data, 'member');
        $branch = Arr::get($data, 'branch');
        $consumeChops = Arr::get($data, 'consumeChops');
        $consumeChopRule = Arr::get($data, 'consumeChopRule');

        return $this->create([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
            'type' => self::CONSUME_CHOPS,
            'chops' => 0,
            'consume_chops' => $consumeChops,
            'rule_id' => optional($consumeChopRule)->id
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
            case self::CONSUME_CHOPS:
                $voidRecord = $this->create([
                    'member_id' => $record->member_id,
                    'branch_id' => $record->branch_id,
                    'type' => self::VOID_CONSUME_CHOPS,
                    'chops' => 0,
                    'consume_chops' => -1 * $record->consume_chops,
                    'void_id' => $record->id
                ]);
                break;
                
            case self::EARN_CHOPS:
                $voidRecord = $this->create([
                    'member_id' => $record->member_id,
                    'branch_id' => $record->branch_id,
                    'type' => self::VOID_EARN_CHOPS,
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
