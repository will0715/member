<?php

namespace App\Listeners;

use App\Services\MemberService;
use App\Services\BranchService;
use App\Services\ChopService;
use App\Services\RegisterChopRuleService;
use Log;

class MemberEventSubscriber
{
    public function __construct() 
    {
        $this->memberService = app(MemberService::class);
        $this->branchService = app(BranchService::class);
        $this->chopService = app(ChopService::class);
        $this->registerChopRuleService = app(RegisterChopRuleService::class);
    }

    public function handleMemberLogin($event) {}

    public function handleNewMember($event) {
        $customer = $event->customer;
        $member = $event->member;

        $this->addRegisterChops($member);
    }

    private function addRegisterChops($member)
    {
        $registerChopRule = $this->registerChopRuleService->getRegisterChopRule();
        if (!$registerChopRule || !$registerChopRule->is_active) {
            return;
        }
        $addChops = $registerChopRule->rule_chops;
        $chopRecordRemark = $registerChopRule->name;

        if ($member->register_branch_id) {
            $this->chopService->manualAddChops([
                'member_id' => $member->id,
                'branch_id' => $member->register_branch_id,
                'chops' => $addChops,
                'remark' => $chopRecordRemark
            ]); 

            Log::info("Member {$member->phone} Register Chops {$addChops}.");
            return;
        }
        $firstBranch = $this->branchService->getFirstBranch();
        if (!$firstBranch) {
            return;
        }

        Log::info("Member {$member->phone} Register Chops {$addChops}.");
        $this->chopService->manualAddChops([
            'member_id' => $member->id,
            'branch_id' => $firstBranch->id,
            'chops' => $addChops,
            'remark' => $chopRecordRemark
        ]);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\MemberLogin',
            'App\Listeners\MemberEventSubscriber@handleUserLogin'
        );

        $events->listen(
            'App\Events\MemberRegistered',
            'App\Listeners\MemberEventSubscriber@handleNewMember'
        );
    }
}