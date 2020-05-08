<?php

namespace App\Repositories;

use App\Criterias\ChopsValidCriteria;
use App\Models\Chop;
use App\Models\ChopExpiredSetting;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

/**
 * Class ChopRepository
 * @package App\Repositories
 * @version April 7, 2020, 2:54 pm UTC
*/

class ChopRepository extends BaseRepository
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

    public function boot()
    {
        $this->pushCriteria(new ChopsValidCriteria());
    }

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
        return Chop::class;
    }

    public function getBranchChops($member, $branch)
    {
        return $this->findWhere([
            'member_id' => $member->id,
            'branch_id' => $branch->id,
        ])->first();
    }

    public function getMemberBranchesChops($member, $branchIds)
    {
        return $this->scopeQuery(function($query) use ($member, $branchIds) {
            return $query->whereIn('branch_id', $branchIds)->where('member_id', $member->id);
        });
    }

    public function addChops($member, $branch, $addChops)
    {
        $chop = $this->getBranchChops($member, $branch);

        $expiredSetting = ChopExpiredSetting::first();

        if (!$chop) {
            $chop = new Chop();
        }
        $chop->chops = $addChops;
        $chop->expired_at = Carbon::now()->add($expiredSetting->expired_date, 'days');
        $chop->member_id = $member->id;
        $chop->branch_id = $branch->id;
        $chop->save();

        return $chop;
    }

    public function consumeChops($member, $branch, $consumeChops)
    {
        $chop = $this->getBranchChops($member, $branch);

        if ($chop) {
            $chop->chops = $chop->chops - $consumeChops;
            $chop->save();
        }

        return $chop;
    }
}
