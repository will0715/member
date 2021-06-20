<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\PickupCouponBranchCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\PromotionRepository;
use App\Repositories\BranchRepository;
use App\Repositories\RankRepository;
use App\Exceptions\ResourceNotFoundException;
use App\Utils\CollectionUtil;
use Cache;
use Arr;

class PromotionService
{
    /** @var  PromotionRepository */
    private $promotionRepository;

    public function __construct()
    {
        $this->promotionRepository = app(PromotionRepository::class);
        $this->branchRepository = app(BranchRepository::class);
        $this->rankRepository = app(RankRepository::class);
    }

    public function listPromotions($request)
    {
        $this->promotionRepository->pushCriteria(new RequestCriteria($request));
        $this->promotionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $promotions = $this->promotionRepository->all();

        return $promotions;
    }

    public function findPromotion($id)
    {
        $promotion = $this->promotionRepository->findWithoutFail($id);
        if (!$promotion) {
            throw new ResourceNotFoundException('Promotion Not Found');
        }
        return $promotion;
    }

    public function findPromotionByCode($code)
    {
        $promotion = $this->promotionRepository->findByCode($code);
        if (!$promotion) {
            throw new ResourceNotFoundException('Promotion Not Found');
        }
        return $promotion;
    }

    public function listPOSPromotions($request)
    {
        $branchCode = $request->get('branch');

        $this->promotionRepository->pushCriteria(new RequestCriteria($request));
        $this->promotionRepository->pushCriteria(new PickupCouponBranchCriteria($request));
        $promotions = $this->promotionRepository->findPOSPromotions();

        return $promotions;
    }

    public function newPromotion($data)
    {
        $promotion = $this->promotionRepository->create($data);
        
        $this->processRequestRelation($promotion, $data);

        return $promotion;
    }

    public function updatePromotion($data, $id)
    {
        $promotion = $this->promotionRepository->update($data, $id);
        
        $this->processRequestRelation($promotion, $data);

        return $promotion;
    }

    private function processRequestRelation($data)
    {
        // limit branches
        $branches = Arr::get($data, 'branches', []);
        if (CollectionUtil::isNotEmpty($branches)) {
            $limitBranches = $this->branchRepository->findInBranchIds($branches);
            $isLimitBranch = collect($limitBranches->pluck('id'))->isNotEmpty();
            $promotion->limitBranches()->sync($limitBranches->pluck('id'));
        }
        
        // limit ranks
        $ranks = Arr::get($data, 'ranks', []);
        if (CollectionUtil::isNotEmpty($ranks)) {
            $limitRanks = $this->rankRepository->findInNames($ranks);
            $isLimitRank = collect($limitRanks->pluck('id'))->isNotEmpty();
            $promotion->limitRanks()->sync($limitRanks->pluck('id'));
        }
    }

    public function deletePromotion($id)
    {
        return $this->promotionRepository->delete($id);
    }
}
