<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\CouponRepository;
use App\Repositories\CouponGroupRepository;
use App\Repositories\MemberRepository;
use App\Repositories\BranchRepository;
use App\Repositories\RankRepository;
use App\Exceptions\CannotUpdateCouponGroupWithCouponsException;
use App\Exceptions\CannotDeleteCouponGroupWithCouponsException;
use App\Exceptions\CouponNotUseableException;
use App\Exceptions\CouponCanNotDisableException;
use App\Models\Coupon;
use App\Models\CouponGroup;
use App\Utils\CollectionUtil;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Arr;

class CouponService
{
    protected $couponRepository;
    protected $couponGroupRepository;
    protected $memberRepository;

    public function __construct(
        CouponRepository $couponRepository,
        CouponGroupRepository $couponGroupRepository,
        MemberRepository $memberRepository,
        BranchRepository $branchRepository,
        RankRepository $rankRepository
    ) {
        $this->couponRepository = $couponRepository;
        $this->couponGroupRepository = $couponGroupRepository;
        $this->memberRepository = $memberRepository;
        $this->branchRepository = $branchRepository;
        $this->rankRepository = $rankRepository;
    }

    public function listCouponGroups($request)
    {
        $this->couponGroupRepository->pushCriteria(new RequestCriteria($request));
        $this->couponGroupRepository->pushCriteria(new LimitOffsetCriteria($request));
        $couponGroups = $this->couponGroupRepository->all();

        return $couponGroups;
    }

    public function findCouponGroup($id)
    {
        $couponGroup = $this->couponGroupRepository->findWithoutFail($id);
        if (!$couponGroup) {
            throw new ResourceNotFoundException('CouponGroup Not Found');
        }
        return $couponGroup;
    }

    public function findCouponGroupByPrefixCode($prefixCode)
    {
        $couponGroup = $this->couponGroupRepository->findByPrefixCode($prefixCode);
        if (!$couponGroup) {
            throw new ResourceNotFoundException('CouponGroup Not Found');
        }
        return $couponGroup;
    }

    public function newCouponGroup($data)
    {
        $couponGroup = $this->couponGroupRepository->create($data);

        $this->processRequestRelation($couponGroup, $data);

        return $couponGroup;
    }

    public function updateCouponGroup($data, $id)
    {
        $couponGroup = $this->findCouponGroup($id);
        if (CollectionUtil::isNotEmpty($couponGroup->coupons)) {
            throw new CannotUpdateCouponGroupWithCouponsException('Cannot update coupon group with coupons');
        }

        $couponGroup = $this->couponGroupRepository->update($data, $id);

        $this->processRequestRelation($couponGroup, $data);

        return $couponGroup;
    }

    public function deleteCouponGroup($id)
    {
        $couponGroup = $this->findCouponGroup($id);
        if (CollectionUtil::isNotEmpty($couponGroup->coupons)) {
            throw new CannotDeleteCouponGroupWithCouponsException('Cannot update coupon group with coupons');
        }

        return $this->couponGroupRepository->delete($id);
    }

    public function listCoupons($request)
    {
        $this->couponRepository->pushCriteria(new RequestCriteria($request));
        $this->couponRepository->pushCriteria(new LimitOffsetCriteria($request));
        $coupons = $this->couponRepository->all();

        return $coupons;
    }

    public function findCoupon($id)
    {
        $coupon = $this->couponRepository->findWithoutFail($id);
        if (!$coupon) {
            throw new ResourceNotFoundException('Coupon Not Found');
        }
        return $coupon;
    }

    public function findCouponByCode($code)
    {
        $coupon = $this->couponRepository->findByCode($code);
        if (!$coupon) {
            throw new ResourceNotFoundException('Coupon Not Found');
        }
        return $coupon;
    }

    public function newCoupon($data)
    {
        $coupon = $this->couponRepository->create($data);

        $this->processRequestRelation($coupon, $data);

        return $coupon;
    }

    public function updateCoupon($data, $id)
    {
        $coupon = $this->couponRepository->update($data, $id);

        $this->processRequestRelation($coupon, $data);

        return $coupon;
    }

    public function deleteCoupon($id)
    {
        return $this->couponRepository->delete($id);
    }

    public function issueCouponToMember($couponGroup, $memberId)
    {
        $member = $this->memberRepository->findWithoutFail($memberId);

        if (!$couponGroup || !$member) {
            return false;
        }

        // 檢查會員是否符合優惠券組的限制條件（如等級限制）
        if (!$this->checkMemberEligibility($member, $couponGroup)) {
            return false;
        }

        $couponData = [
            'coupon_group_id' => $couponGroup->id,
            'member_id' => $memberId,
            'code' => $this->generateUniqueCode($couponGroup->prefix_code),
            'status' => Coupon::STATUS_AVAILABLE,
            'claimed_at' => Carbon::now(),
        ];

        // 設置有效期
        if ($couponGroup->calculate_time_unit === CouponGroup::CALCULATE_TIME_UNIT_FIXED) {
            $couponData['effective_start_at'] = $couponGroup->fixed_start_time;
            $couponData['expired_at'] = $couponGroup->fixed_end_time;
        } elseif ($couponGroup->calculate_time_unit === CouponGroup::CALCULATE_TIME_UNIT_CLAIM) {
            $couponData['effective_start_at'] = Carbon::now();
            $couponData['expired_at'] = Carbon::now()->addDays($couponGroup->valid_days_after_claim);
        }

        $coupon = $this->couponRepository->create($couponData);
        $coupon->load('couponGroup');

        return $coupon;
    }

    protected function checkMemberEligibility($member, $couponGroup)
    {
        // 檢查等級限制
        if ($couponGroup->limit_rank && !$couponGroup->limitRanks->contains($member->rank_id)) {
            return false;
        }

        // 可以添加更多的限制條件檢查

        return true;
    }

    public function issueCouponGroupToMembers($couponGroupId, $memberIds)
    {
        $issuedCoupons = [];

        $couponGroup = $this->couponGroupRepository->findWithoutFail($couponGroupId);

        foreach ($memberIds as $memberId) {
            $coupon = $this->issueCouponToMember($couponGroup, $memberId);
            if ($coupon) {
                $issuedCoupons[] = $coupon;
            }
        }

        return $issuedCoupons;
    }

    public function getAvailableCouponsForMember($memberId)
    {
        return $this->couponRepository->getAvailableCouponsForMember($memberId);
    }

    public function getAllCouponsForMember($memberId)
    {
        return $this->couponRepository->findWhere(['member_id' => $memberId]);
    }

    public function useCoupon($code, $data)
    {
        $coupon = $this->couponRepository->findByCode($code);
        $couponGroup = $coupon->couponGroup;
        $usageMemberId = Arr::get($data, 'member_id');
        $usageData = Arr::get($data, 'usage_data');
        $usageBranchId = Arr::get($usageData, 'branch_id');

        if (!$coupon || !$coupon->isUseable()) {
            throw new CouponNotUseableException('Coupon is not useable');
        }

        if ($coupon->member_id != $usageMemberId) {
            throw new CouponNotUseableException('Coupon is not for this member');
        }

        if ($couponGroup->limit_branch && !$couponGroup->limitBranches->pluck("code")->contains($usageBranchId)) {
            throw new CouponNotUseableException('Coupon is not for this branch');
        }

        return $this->couponRepository->useCoupon($usageData ,$coupon->id);
    }

    public function disableCoupon($code)
    {
        $coupon = $this->couponRepository->findByCode($code);

        if (!$coupon || $coupon->isUsed()) {
            throw new CouponCanNotDisableException('Coupon is already used');
        }

        return $this->couponRepository->disableCoupon($coupon->id);
    }

    protected function generateUniqueCode($prefix)
    {
        do {
            $code = $this->generateCouponCode($prefix);
        } while ($this->couponRepository->codeExists($code));

        return $code;
    }

    protected function generateCouponCode($prefix)
    {
        // 32^8 = 1,099,511,627,776 possibilities
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < 8; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $prefix . $code;
    }

    private function processRequestRelation($promotion, $data)
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
}

