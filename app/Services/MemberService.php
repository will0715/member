<?php

namespace App\Services;

use App\Models\Member;
use App\Repositories\MemberRepository;
use App\Repositories\RankRepository;
use App\Events\MemberRegistered;
use Cache;

class MemberService
{
    /** @var  MemberRepository */
    private $memberRepository;
    private $customer;

    public function __construct($customer = '')
    {
        $this->memberRepository = app(MemberRepository::class);
        $this->rankRepository = app(RankRepository::class);
        $this->customer = $customer;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    public function newMember($data)
    {
        if (!isset($data['rank_id'])) {
            $basicMemberRank = $this->getBasicMemberRank();
            $data['rank_id'] = $basicMemberRank->id; 
        }
        $member = $this->memberRepository->newMember($data);

        event(new MemberRegistered($this->customer, $member));

        return $member;
    }

    public function updateMember($data, $id)
    {
        $member = $this->memberRepository->updateMember($data, $id);
        return $member;
    }

    public function getBasicMemberRank()
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
}
