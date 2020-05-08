<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMemberAPIRequest;
use App\Http\Requests\API\UpdateMemberAPIRequest;
use App\Models\Member;
use App\Repositories\MemberRepository;
use App\Repositories\BranchRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Events\MemberRegistered;
use App\Services\MemberService;
use App\Services\ChopService;
use Response;
use Hash;
use Log;

/**
 * Class MemberAPIController
 * @package App\Http\Controllers
 */

class MemberAPIController extends AppBaseController
{
    /** @var  MemberRepository */
    private $memberRepository;

    public function __construct()
    {
        $this->memberRepository = app(MemberRepository::class);
        $this->branchRepository = app(BranchRepository::class);
        $this->memberService = new MemberService();
        $this->chopService = new ChopService();
    }

    /**
     * Display a listing of the Member.
     * GET|HEAD /members
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->memberRepository->pushCriteria(new RequestCriteria($request));
        $this->memberRepository->pushCriteria(new LimitOffsetCriteria($request));
        $members = $this->memberRepository->with(['rank', 'chops'])->all();

        return $this->sendResponse($members->toArray(), 'Members retrieved successfully');
    }

    /**
     * Store a newly created Member in storage.
     * POST /members
     *
     * @param CreateMemberAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMemberAPIRequest $request)
    {
        $customer = $this->getCustomerName($request);
        $input = $request->all();

        try {
            $this->memberService->setCustomer($customer);
            $member = $this->memberService->newMember($input);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('New Member failed', 500);
        }

        return $this->sendResponse($member->toArray(), 'Member saved successfully');
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Member $member */
        $member = $this->memberRepository->with(['rank', 'chops'])->find($id);

        if (empty($member)) {
            return $this->sendError('Member not found');
        }

        return $this->sendResponse($member->toArray(), 'Member retrieved successfully');
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function detail($id)
    {
        /** @var Member $member */
        $member = $this->memberRepository->with(['rank', 'chops', 'chops.branch', 'orderRecords', 'orderRecords.branch', 'orderRecords.transactionItems', 'orderRecords.chopRecords', 'chopRecords', 'chopRecords.branch', 'chopRecords.voidRecord'])->find($id);

        if (empty($member)) {
            return $this->sendError('Member not found');
        }

        return $this->sendResponse($member, 'Member retrieved successfully');
    }

    /**
     * Update the specified Member in storage.
     * PUT/PATCH /members/{id}
     *
     * @param int $id
     * @param UpdateMemberAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMemberAPIRequest $request)
    {
        $customer = $this->getCustomerName($request);
        $input = $request->all();

        /** @var Member $member */
        $member = $this->memberRepository->find($id);

        if (empty($member)) {
            return $this->sendError('Member not found');
        }

        try {
            $this->memberService->setCustomer($customer);
            $member = $this->memberService->updateMember($input, $id);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Update Member failed', 500);
        }

        return $this->sendResponse($member->toArray(), 'Member updated successfully');
    }

    /**
     * Remove the specified Member from storage.
     * DELETE /members/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Member $member */
        $member = $this->memberRepository->find($id);

        if (empty($member)) {
            return $this->sendError('Member not found');
        }

        $member->delete();

        return $this->sendSuccess('Member deleted successfully');
    }

    public function queryByPhone(Request $request)
    {
        $phone = $request->get('phone');
        $branchId = $request->get('branch_id');

        $member = $this->memberRepository->findByPhone($phone);
        if (!$member) {
            return $this->sendError('member not exist', 404);
        }
    
        $branch = $this->branchRepository->findByBranchId($branchId);
        if (!$branch) {
            return $this->sendError('branch not exist', 404);
        }

        try {
            $chops = $this->chopService->getTotalChops($member, $branch);
            $member = $member->toArray();
            $member['chops'] = $chops;
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Query Member failed', 500);
        }
        
        return $this->sendResponse($member, 'Member updated successfully');
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function getChops($phone)
    {
        /** @var Member $member */
        $member = $this->memberRepository->findByPhone($phone);

        if (empty($member)) {
            return $this->sendError('Member not found');
        }

        return $this->sendResponse(['chops' => $member->chops->sum('chops')], 'Member retrieved successfully');
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function getChopsDetail($phone)
    {
        /** @var Member $member */
        $member = $this->memberRepository->with('chops.branch')->findByPhone($phone);

        if (empty($member)) {
            return $this->sendError('Member not found');
        }

        return $this->sendResponse($member->chops, 'Member retrieved successfully');
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function getOrderRecords($phone)
    {
        /** @var Member $member */
        $member = $this->memberRepository->with(['orderRecords', 'orderRecords.branch', 'orderRecords.transactionItems', 'orderRecords.chopRecords'])->findByPhone($phone);

        if (empty($member)) {
            return $this->sendError('Member not found');
        }

        return $this->sendResponse($member->orderRecords, 'Member retrieved successfully');
    }
}
