<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Constants\MemberConstant;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\MemberRepository;
use App\Repositories\RankRepository;
use App\Repositories\MemberSocialiteRepository;
use App\Helpers\CustomerHelper;
use App\Events\MemberRegistered;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\SearchFieldEmptyException;
use App\Models\Member;
use Poyi\PGSchema\Facades\PGSchema;
use Illuminate\Auth\AuthenticationException;
use Carbon\Carbon;
use Arr;
use Auth;
use Cache;
use Hash;

class MemberService
{
    
    /** @var  MemberRepository */
    private $memberRepository;
    private $customer;

    public function __construct($customer = '')
    {
        $this->memberRepository = app(MemberRepository::class);
        $this->rankRepository = app(RankRepository::class);
        $this->memberSocialiteRepository = app(MemberSocialiteRepository::class);
    }

    public function listMembers($request)
    {
        $this->memberRepository->pushCriteria(new RequestCriteria($request));
        $this->memberRepository->pushCriteria(new LimitOffsetCriteria($request));
        $members = $this->memberRepository->all();

        return $members;
    }

    public function memberSimpleList($request)
    {
        $this->memberRepository->pushCriteria(new RequestCriteria($request));
        $this->memberRepository->pushCriteria(new LimitOffsetCriteria($request));
        $members = $this->memberRepository->listWithChopsCount()->get();

        return $members;
    }

    public function memberBasicInfo($id)
    {
        $members = $this->memberRepository->findWithChopsBalance($id);

        return $members;
    }

    public function findMember($id)
    {
        $member = $this->memberRepository->findWithoutFail($id);
        if (!$member) {
            throw new ResourceNotFoundException('Member Not Found');
        }
        return $member;
    }

    public function findMemberByPhone($phone)
    {
        $member = $this->memberRepository->findByPhone($phone);
        if (!$member) {
            throw new ResourceNotFoundException('Member Not Found');
        }
        return $member;
    }

    public function findMemberByQuerySearch(array $search): Member
    {
        if (empty($search)) {
            throw new SearchFieldEmptyException();
        }

        $member = $this->memberRepository->findFirstBySearchableField($search);
        if (!$member) {
            throw new ResourceNotFoundException('Member Not Found');
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
        return $this->memberRepository->delete($id);
    }

    public function forceDeleteMember($id)
    {
        $member = $this->memberRepository->forceDelete($id);
        return $member;
    }

    public function login($attributes)
    {
        $phone = $attributes['phone'];
        $password = $attributes['password'];
        
        $member = $this->findMemberByPhone($phone);

        if ($this->checkMemberPassword($member, $password)) {
            return $member;
        } else {
            throw new AuthenticationException();
    	}
    }

    public function loginWithSocialite($attributes)
    {
        $socialiteProvider = $attributes['socialiteProvider'];
        $userId = $attributes['userId'];
        
        $memberSocialiteData = $this->memberSocialiteRepository->findBySocialiteUserId($socialiteProvider, $userId);
        if (!$memberSocialiteData) {
            throw new AuthenticationException();
        }
        
        $member = $this->findMember($memberSocialiteData->member_id);

        return $member;
    }

    public function checkMemberPassword($member, $password)
    {
        return Hash::check($password, $member->password);
    }

    public function bindMemberLineId($memberId, $lineUserId)
    {
        $memberSocialiteData = $this->memberSocialiteRepository->create([
            'member_id' => $memberId,
            'socialite_provider' => 'line',
            'socialite_user_id' => $lineUserId
        ]);

        return $memberSocialiteData;
    }
}
