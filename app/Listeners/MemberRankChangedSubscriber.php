<?php

namespace App\Listeners;

use App\Events\MemberRankChanged;
use App\Services\CouponService;
use App\Services\RankService;

class MemberRankChangedSubscriber
{
    public function __construct()
    {
        $this->couponService = app(CouponService::class);
        $this->rankService = app(RankService::class);
    }

    public function handleRankChangedIssueCoupon(MemberRankChanged $event) {
        $customer = $event->customer;
        $member = $event->member;
        $newRankId = $event->newRankId;
        $issueCoupon = $event->issueCoupon;

        if ($issueCoupon) {
            $this->issueCoupon($customer, $member, $newRankId);
        }
    }

    private function issueCoupon($customer, $member, $newRankId) {
        $rank = $this->rankService->findRank($newRankId);
        if (!$rank) {
            return;
        }
        $upgradeSetting = $this->rankService->getRankUpgradeSetting($rank->id);
        if (!$upgradeSetting || !$upgradeSetting->issue_coupon) {
            return;
        }
        $couponGroup = $upgradeSetting->issueCouponGroup;
        if (!$couponGroup) {
            return;
        }
        $quantity = $upgradeSetting->issue_coupon_quantity;

        $this->couponService->issueCouponToMember($couponGroup, $member->id, $quantity);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {

        $events->listen(
            'App\Events\MemberRankChanged',
            'App\Listeners\MemberRankChangedSubscriber@handleRankChangedIssueCoupon'
        );
    }
}
