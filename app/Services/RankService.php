<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\RankRepository;
use App\Repositories\RankDiscountRepository;
use App\Helpers\CustomerHelper;
use App\Exceptions\ResourceNotFoundException;
use Cache;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class RankService
{

    public function __construct()
    {
        $this->rankRepository = app(RankRepository::class);
        $this->rankDiscountRepository = app(RankDiscountRepository::class);
    }

    public function listRanks($request)
    {
        $this->rankRepository->pushCriteria(new RequestCriteria($request));
        $this->rankRepository->pushCriteria(new LimitOffsetCriteria($request));
        $ranks = $this->rankRepository->all();

        return $ranks;
    }

    public function findRank($id)
    {
        $rank = $this->rankRepository->findWithoutFail($id);
        if (!$rank) {
            throw new ResourceNotFoundException('Rank Not Found');
        }
        return $rank;
    }

    public function newRank($data)
    {
        $rank = $this->rankRepository->create($data);
        return $rank;
    }

    public function updateRank($data, $id)
    {
        $rank = $this->rankRepository->update($data, $id);
        return $rank;
    }

    public function deleteRank($id)
    {
        $rank = $this->findRank($id);
        if ($rank->members->count() > 0) {
            throw new ConflictHttpException('The rank has members. Can not be deleted.');
        }
        return $this->rankRepository->delete($id);
    }

    public function getBasicRank()
    {
        $basicMemberRank = $this->rankRepository->getBasicRank();
        // TODO:: add cache
        // $basicMemberRank = Cache::get($this->customer . 'basicMemberRank');
        // if (!$basicMemberRank) {
        //     $basicMemberRank = $this->rankRepository->getBasicRank();
        //     Cache::set($this->customer . 'basicMemberRank', $basicMemberRank);
        // }
        return $basicMemberRank;
    }

    public function listRankDiscounts($request)
    {
        $this->rankDiscountRepository->pushCriteria(new RequestCriteria($request));
        $this->rankDiscountRepository->pushCriteria(new LimitOffsetCriteria($request));
        $rankDiscounts = $this->rankDiscountRepository->all();

        return $rankDiscounts;
    }

    public function getRankDiscount($rankId)
    {
        $rankDiscount = $this->rankDiscountRepository->findByRankId($rankId);
        if (!$rankDiscount) {
            $rankDiscount = $this->rankDiscountRepository->getDefaultRankDiscount($rankId);
        }
        return $rankDiscount;
    }

    public function setRankDiscount($data, $rankId)
    {
        $attributes = [
            'is_active' => $data['is_active'],
            'content' => $data['content'],
        ];
        $rankDiscount = $this->rankDiscountRepository->updateOrCreate([
            'rank_id' => $rankId
        ], $attributes);
        return $rankDiscount;
    }
}
