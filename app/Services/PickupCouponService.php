<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Criterias\PhoneCriteria;
use App\Criterias\PickupCouponRemainedCriteria;
use App\Criterias\PickupCouponBranchCriteria;
use App\Repositories\MemberRepository;
use App\Repositories\BranchRepository;
use App\Repositories\PickupCouponRepository;
use App\Repositories\PickupCouponConsumedHistoryRepository;
use App\Exceptions\QuantityNotEnoughException;
use App\Exceptions\BranchPermissonDeniedException;
use App\Exceptions\ResourceNotFoundException;
use App\Utils\StringUtil;
use Cache;
use Carbon\Carbon;
use Arr;

class PickupCouponService
{

    public function __construct()
    {
        $this->memberRepository = app(MemberRepository::class);
        $this->branchRepository = app(BranchRepository::class);
        $this->pickupCouponRepository = app(PickupCouponRepository::class);
        $this->pickupCouponConsumedHistoryRepository = app(PickupCouponConsumedHistoryRepository::class);
    }

    public function listPickupCoupons($request)
    {
        $this->pickupCouponRepository->pushCriteria(new RequestCriteria($request));
        $this->pickupCouponRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->pickupCouponRepository->pushCriteria(new PhoneCriteria($request));
        $this->pickupCouponRepository->pushCriteria(new PickupCouponBranchCriteria($request));

        $pickupCoupons = $this->pickupCouponRepository->all();

        return $pickupCoupons;
    }

    public function listByBranch($request)
    {
        $branchCode = $request->get('branch');

        $this->pickupCouponRepository->pushCriteria(new RequestCriteria($request));
        $this->pickupCouponRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->pickupCouponRepository->pushCriteria(new PhoneCriteria($request));
        $this->pickupCouponRepository->pushCriteria(new PickupCouponBranchCriteria($request));
        $this->pickupCouponRepository->pushCriteria(PickupCouponRemainedCriteria::createByRequest($request, 'remained'));
        
        $pickupCoupons = $this->pickupCouponRepository->all();

        return $pickupCoupons;
    }

    public function listByMemberPhone($phone)
    {
        $this->pickupCouponRepository->pushCriteria(new RequestCriteria($request));
        $this->pickupCouponRepository->pushCriteria(new LimitOffsetCriteria($request));
        $pickupCoupons = $this->pickupCouponRepository->findByPhone($phone);

        return $pickupCoupons;
    }

    public function findPickupCoupon($id)
    {
        $pickupCoupon = $this->pickupCouponRepository->findWithoutFail($id);
        if (!$pickupCoupon) {
            throw new ResourceNotFoundException('PickupCoupon Not Found');
        }
        return $pickupCoupon;
    }

    public function findPickupCouponByCode($code)
    {
        $pickupCoupon = $this->pickupCouponRepository->findByCode($code);
        if (!$pickupCoupon) {
            throw new ResourceNotFoundException('PickupCoupon Not Found');
        }
        return $pickupCoupon;
    }

    public function newPickupCoupon($data)
    {
        $branches = Arr::get($data, 'branches', []);
        $limitBranches = $this->branchRepository->findWhereIn('code', $branches);

        $isLimitBranch = collect($limitBranches->pluck('id'))->isNotEmpty();

        $pickupCoupon = $this->pickupCouponRepository->create(array_merge($data, [
            'consumed_quantity' => 0,
            'limit_branch' => $isLimitBranch,
            'code' => StringUtil::generateRandomString()
        ]));
        
        // limit branches
        $pickupCoupon->limitBranches()->sync($limitBranches->pluck('id'));

        return $pickupCoupon;
    }

    public function consumePickupCoupon($data, $code)
    {
        $consumedAt = Carbon::now();
        $consumedQuantity = $data['consumed_quantity'];
        $branch = $data['branch'];
        $remark = $data['remark'];

        $pickupCoupon = $this->findPickupCouponByCode($code);
        $remainedQuantity = $pickupCoupon->quantity - $pickupCoupon->consumed_quantity;
        if ($remainedQuantity < $consumedQuantity) {
            throw new QuantityNotEnoughException();
        }

        if (!$this->branchCanUse($pickupCoupon, $branch)) {
            throw new BranchPermissonDeniedException();
        }

        $consumeData = [
            'consumed_quantity' => $pickupCoupon->consumed_quantity + $consumedQuantity,
            'last_consumed_at' => $consumedAt,
        ];
        $pickupCoupon = $this->pickupCouponRepository->update($consumeData, $pickupCoupon->id);

        $this->pickupCouponConsumedHistoryRepository->create([
            'pickup_coupon_id' => $pickupCoupon->id,
            'consumed_quantity' => $consumedQuantity,
            'consumed_branch' => $branch,
            'remark' => $remark,
            'consumed_at' => $consumedAt,
        ]);

        return $pickupCoupon;
    }

    public function updatePickupCoupon($data, $id)
    {
        $branches = Arr::get($data, 'branches', []);
        $limitBranches = $this->branchRepository->findWhereIn('code', $branches);

        $isLimitBranch = collect($limitBranches->pluck('id'))->isNotEmpty();

        $pickupCoupon = $this->pickupCouponRepository->update(array_merge($data, [
            'limit_branch' => $isLimitBranch,
        ]), $id);
        
        // limit branches
        $pickupCoupon->limitBranches()->sync($limitBranches->pluck('id'));

        return $pickupCoupon;
    }

    public function deletePickupCoupon($id)
    {
        $pickupCoupon = $this->findPickupCoupon($id);
        return $this->pickupCouponRepository->delete($id);
    }

    public function giveTo($data, $id)
    {
        $giveQuantity = $data['quantity'];
        $targetPhone = $data['target'];
        
        $member = $this->memberRepository->findByPhone($targetPhone);
        if (!$member) {
            throw new ResourceNotFoundException('Target Member Not Found');
        }

        $pickupCoupon = $this->findPickupCoupon($id);
        $remainedQuantity = $pickupCoupon->quantity - $pickupCoupon->consumed_quantity;
        if ($remainedQuantity < $giveQuantity) {
            throw new QuantityNotEnoughException();
        }

        $pickupCoupon = $this->pickupCouponRepository->update([
            'quantity' => $pickupCoupon->quantity - $giveQuantity
        ], $id);
        $newPickupCoupon = $this->pickupCouponRepository->create([
            'phone' => $targetPhone,
            'product_name' => $pickupCoupon->product_name,
            'product_no' => $pickupCoupon->product_no,
            'quantity' => $giveQuantity,
            'condiment' => $pickupCoupon->condiment,
            'remark' => $pickupCoupon->remark,
            'consumed_quantity' => 0,
            'price' => $pickupCoupon->price,
            'expired_at' => $pickupCoupon->expired_at,
            'limit_branch' => $pickupCoupon->limit_branch,
            'code' => StringUtil::generateRandomString()
        ]);

        // copy limit branch
        $limitBranches = $pickupCoupon->limitBranches;
        $newPickupCoupon->limitBranches()->sync($limitBranches->pluck('id'));

        return $newPickupCoupon;
    }

    private function branchCanUse($pickupCoupon, $branch)
    {
        $limitBranches = $pickupCoupon->limitBranches;
        if ($limitBranches->isEmpty()) {
            return true;
        }
        if ($limitBranches->firstWhere('code', $branch)) {
            return true;
        }
        return false;
    }
}
