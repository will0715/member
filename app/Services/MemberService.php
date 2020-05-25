<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Constants\MemberConstant;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\MemberRepository;
use App\Repositories\RankRepository;
use App\Helpers\CustomerHelper;
use App\Events\MemberRegistered;
use App\Exceptions\ResourceNotFoundException;
use Poyi\PGSchema\Facades\PGSchema;
use Arr;
use Auth;
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
    }

    public function listMembers($request)
    {
        $this->memberRepository->pushCriteria(new RequestCriteria($request));
        $this->memberRepository->pushCriteria(new LimitOffsetCriteria($request));
        $members = $this->memberRepository->all();

        return $members;
    }

    public function findMember($id)
    {
        $member = $this->memberRepository->findWithoutFail($id);
        if (!$member) {
            throw new ResourceNotFoundException('Member not exist');
        }
        return $member;
    }

    public function findMemberByPhone($phone)
    {
        $member = $this->memberRepository->findByPhone($phone);
        if (!$member) {
            throw new ResourceNotFoundException('Member not exist');
        }
        return $member;
    }

    public function newMember($data)
    {
        $customer = CustomerHelper::getCustomer();
        $member = $this->memberRepository->newMember($data);

        event(new MemberRegistered($customer, $member));

        return $member;
    }

    public function updateMember($data, $id)
    {
        $member = $this->memberRepository->updateMember($data, $id);
        return $member;
    }

    public function updateMemberByPhone($data, $phone)
    {
        $oldMember = $this->findMemberByPhone($phone);

        // only can edit some column
        $member = $this->memberRepository->updateMember(Arr::only($data, [
            'first_name',
            'last_name',
            'password',
            'gender',
            'email',
            'address',
            'birthday',
            'remark',
        ]), $oldMember->id);

        return $member;
    }

    public function deleteMember($id)
    {
        $member = $this->findMember($id);
        $member->delete();
        return $member;
    }
}
